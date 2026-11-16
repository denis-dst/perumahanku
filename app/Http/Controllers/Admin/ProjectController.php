<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount(['houseTypes', 'units'])
            ->orderBy('sort_order')
            ->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'facilities' => 'nullable|string',
            'siteplan_image' => 'nullable|string',
            'status' => 'required|in:active,coming_soon,sold_out',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        // Ensure unique slug
        $count = Project::where('slug', 'like', "{$validated['slug']}%")->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        // Facilities parse from comma or newline separated
        if (!empty($validated['facilities'])) {
            $lines = preg_split('/[\r\n,]+/', $validated['facilities']);
            $validated['facilities'] = array_values(array_filter(array_map('trim', $lines)));
        } else {
            $validated['facilities'] = [];
        }

        $validated['is_featured'] = $request->has('is_featured');

        $project = Project::create($validated);

        // Upload/primary photo if provided
        if ($request->filled('primary_photo_url')) {
            $project->images()->create([
                'path' => $request->primary_photo_url,
                'caption' => 'Foto Utama ' . $project->name,
                'is_primary' => true,
                'category' => 'exterior',
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Proyek perumahan berhasil ditambahkan!');
    }

    public function edit(Project $project)
    {
        $project->load(['houseTypes', 'images']);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'facilities' => 'nullable|string',
            'siteplan_image' => 'nullable|string',
            'status' => 'required|in:active,coming_soon,sold_out',
            'is_featured' => 'boolean',
        ]);

        if (!empty($validated['facilities'])) {
            $lines = preg_split('/[\r\n,]+/', $validated['facilities']);
            $validated['facilities'] = array_values(array_filter(array_map('trim', $lines)));
        } else {
            $validated['facilities'] = [];
        }

        $validated['is_featured'] = $request->has('is_featured');

        $project->update($validated);

        if ($request->filled('primary_photo_url')) {
            $project->images()->where('is_primary', true)->delete();
            $project->images()->create([
                'path' => $request->primary_photo_url,
                'caption' => 'Foto Utama ' . $project->name,
                'is_primary' => true,
                'category' => 'exterior',
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Perumahan berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
