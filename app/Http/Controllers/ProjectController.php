<?php

namespace App\Http\Controllers;

use App\Models\KprBank;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['primaryImage', 'houseTypes', 'units'])
            ->active();

        // Filter kota
        if ($request->filled('city')) {
            $cityVal = $request->city;
            $cleanCity = trim(preg_replace('/^(kabupaten|kota)\s+/i', '', $cityVal));
            $query->where(function ($sub) use ($cityVal, $cleanCity) {
                $sub->where('city', $cityVal)
                    ->orWhere('city', 'like', "%{$cleanCity}%");
            });
        }

        // Filter keyword (nama atau deskripsi atau kecamatan)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('district', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter harga
        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->max_price;
            $query->whereHas('houseTypes', function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            });
        }

        if ($request->filled('min_price')) {
            $minPrice = (float) $request->min_price;
            $query->whereHas('houseTypes', function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'termurah' => $query->orderBy('sort_order'),
            'terpopuler' => $query->orderByDesc('is_featured'),
            default => $query->latest(),
        };

        $projects = $query->paginate(9)->withQueryString();
        $cities = Project::LAMPUNG_CITIES;

        return view('projects.index', compact('projects', 'cities'));
    }

    public function show(string $slug)
    {
        $project = Project::with([
            'houseTypes.images',
            'units' => function ($q) {
                $q->with(['houseType.images', 'images', 'povImages', 'facadeImages', 'floorPlanImages']);
            },
            'images',
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        $banks = KprBank::active()->get();
        $otherProjects = Project::active()
            ->where('id', '!=', $project->id)
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'banks', 'otherProjects'));
    }

    public function cityLanding(string $city)
    {
        $cityFormatted = ucwords(str_replace('-', ' ', $city));
        $cleanCity = trim(preg_replace('/^(kabupaten|kota)\s+/i', '', $cityFormatted));

        $projects = Project::with(['primaryImage', 'houseTypes', 'units'])
            ->active()
            ->where(function ($q) use ($cityFormatted, $city, $cleanCity) {
                $q->where('city', 'like', "%{$cityFormatted}%")
                    ->orWhere('city', 'like', "%{$cleanCity}%")
                    ->orWhere('city', 'like', "%{$city}%");
            })
            ->latest()
            ->paginate(9);

        $cities = Project::LAMPUNG_CITIES;

        return view('projects.city', compact('projects', 'cityFormatted', 'cities'));
    }
}
