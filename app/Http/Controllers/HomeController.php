<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\KprBank;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::with(['primaryImage', 'houseTypes'])
            ->active()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $cities = Project::active()->distinct()->pluck('city')->filter()->values();

        $banks = KprBank::active()->take(4)->get();

        $latestArticles = BlogPost::published()->take(3)->get();

        $totalUnits = \App\Models\Unit::count();
        $availableUnits = \App\Models\Unit::where('status', 'available')->count();
        $totalProjects = Project::active()->count();

        return view('home', compact(
            'featuredProjects',
            'cities',
            'banks',
            'latestArticles',
            'totalUnits',
            'availableUnits',
            'totalProjects'
        ));
    }
}
