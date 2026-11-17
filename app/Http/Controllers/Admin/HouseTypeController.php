<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HouseType;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HouseTypeController extends Controller
{
    public function index(Project $project)
    {
        $houseTypes = $project->houseTypes()->withCount('units')->get();

        return view('admin.house_types.index', compact('project', 'houseTypes'));
    }

    public function create(Project $project)
    {
        return view('admin.house_types.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'building_area' => 'nullable|integer|min:1',
            'land_area' => 'nullable|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'floors' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'floor_plan_image' => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $validated['project_id'] = $project->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->has('is_available');

        // Parse specifications
        if ($request->filled('specs_keys') && is_array($request->specs_keys)) {
            $specs = [];
            foreach ($request->specs_keys as $idx => $key) {
                if (! empty($key) && isset($request->specs_values[$idx])) {
                    $specs[trim($key)] = trim($request->specs_values[$idx]);
                }
            }
            $validated['specifications'] = $specs;
        }

        $type = HouseType::create($validated);

        return redirect()->route('admin.projects.house-types.index', $project)->with('success', 'Tipe rumah berhasil ditambahkan!');
    }

    public function edit(Project $project, HouseType $houseType)
    {
        return view('admin.house_types.edit', compact('project', 'houseType'));
    }

    public function update(Request $request, Project $project, HouseType $houseType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'building_area' => 'nullable|integer|min:1',
            'land_area' => 'nullable|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'floors' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'floor_plan_image' => 'nullable|string',
            'is_available' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->has('is_available');

        if ($request->filled('specs_keys') && is_array($request->specs_keys)) {
            $specs = [];
            foreach ($request->specs_keys as $idx => $key) {
                if (! empty($key) && isset($request->specs_values[$idx])) {
                    $specs[trim($key)] = trim($request->specs_values[$idx]);
                }
            }
            $validated['specifications'] = $specs;
        }

        $houseType->update($validated);

        return redirect()->route('admin.projects.house-types.index', $project)->with('success', 'Tipe rumah berhasil diperbarui!');
    }

    public function destroy(Project $project, HouseType $houseType)
    {
        $houseType->delete();

        return redirect()->route('admin.projects.house-types.index', $project)->with('success', 'Tipe rumah berhasil dihapus.');
    }
}
