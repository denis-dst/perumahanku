<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Project $project)
    {
        $units = $project->units()->with(['houseType', 'povImages'])->latest()->paginate(15);
        $houseTypes = $project->houseTypes;

        return view('admin.units.index', compact('project', 'units', 'houseTypes'));
    }

    public function create(Project $project)
    {
        $houseTypes = $project->houseTypes;

        return view('admin.units.create', compact('project', 'houseTypes'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'house_type_id' => 'required|exists:house_types,id',
            'block' => 'nullable|string|max:20',
            'number' => 'required|string|max:20',
            'land_area' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'facing' => 'nullable|string|max:100',
            'status' => 'required|in:available,booked,sold',
            'pov_description' => 'nullable|string',
            'position_x' => 'nullable|numeric|between:0,100',
            'position_y' => 'nullable|numeric|between:0,100',
        ]);

        $validated['project_id'] = $project->id;
        $unit = Unit::create($validated);

        if ($request->filled('pov_photo_url')) {
            $unit->images()->create([
                'path' => $request->pov_photo_url,
                'caption' => 'POV dari Kavling '.$unit->block.'-'.$unit->number,
                'is_primary' => true,
                'category' => 'pov',
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.projects.units.index', $project)->with('success', 'Kavling berhasil ditambahkan!');
    }

    public function edit(Project $project, Unit $unit)
    {
        $houseTypes = $project->houseTypes;
        $unit->load('povImages');

        return view('admin.units.edit', compact('project', 'unit', 'houseTypes'));
    }

    public function update(Request $request, Project $project, Unit $unit)
    {
        $validated = $request->validate([
            'house_type_id' => 'required|exists:house_types,id',
            'block' => 'nullable|string|max:20',
            'number' => 'required|string|max:20',
            'land_area' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'facing' => 'nullable|string|max:100',
            'status' => 'required|in:available,booked,sold',
            'pov_description' => 'nullable|string',
            'position_x' => 'nullable|numeric|between:0,100',
            'position_y' => 'nullable|numeric|between:0,100',
        ]);

        $unit->update($validated);

        if ($request->filled('pov_photo_url')) {
            $unit->images()->where('category', 'pov')->delete();
            $unit->images()->create([
                'path' => $request->pov_photo_url,
                'caption' => 'POV dari Kavling '.$unit->block.'-'.$unit->number,
                'is_primary' => true,
                'category' => 'pov',
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.projects.units.index', $project)->with('success', 'Kavling berhasil diperbarui!');
    }

    public function destroy(Project $project, Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.projects.units.index', $project)->with('success', 'Kavling berhasil dihapus.');
    }
}
