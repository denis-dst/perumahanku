<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Unit;
use App\Services\SiteplanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteplanMapperController extends Controller
{
    protected SiteplanService $siteplanService;

    public function __construct(SiteplanService $siteplanService)
    {
        $this->siteplanService = $siteplanService;
    }

    public function index(Project $project)
    {
        $project->load(['houseTypes', 'units.povImages']);
        $units = $project->units;
        $houseTypes = $project->houseTypes;

        return view('admin.siteplan.mapper', compact('project', 'units', 'houseTypes'));
    }

    /**
     * Upload new siteplan image file
     */
    public function uploadImage(Request $request, Project $project)
    {
        $request->validate([
            'siteplan_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $file = $request->file('siteplan_file');
        $filename = 'siteplan_' . $project->slug . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('siteplans', $filename, 'public');

        $project->update([
            'siteplan_image' => 'storage/' . $path,
        ]);

        return redirect()->back()->with('success', 'Gambar siteplan berhasil diunggah! Silakan lakukan slice kavling.');
    }

    /**
     * Trigger auto-slice detection for standard or sample siteplans
     */
    public function autoSlice(Project $project)
    {
        // Check image file
        $siteplanPath = null;
        if ($project->siteplan_image) {
            $cleaned = preg_replace('#^/?storage/#', '', $project->siteplan_image);
            $local = storage_path('app/public/' . $cleaned);
            if (file_exists($local)) {
                $siteplanPath = $local;
            } elseif (file_exists(public_path('storage/' . $cleaned))) {
                $siteplanPath = public_path('storage/' . $cleaned);
            }
        }

        if (!$siteplanPath || !file_exists($siteplanPath)) {
            // Fallback to sample-siteplan if exists
            $sample = storage_path('app/public/sample-siteplan.jpg');
            if (file_exists($sample)) {
                $siteplanPath = $sample;
            } else {
                return redirect()->back()->with('error', 'File gambar siteplan tidak ditemukan di server.');
            }
        }

        $count = $this->siteplanService->autoSliceSampleSiteplan($project, $siteplanPath);

        return redirect()->back()->with('success', "Berhasil melakukan auto-slice {$count} unit kavling dengan foto potongan arsiran dan POV nyata!");
    }

    /**
     * Reset all sliced units & coordinates for this project
     */
    public function resetSlices(Project $project)
    {
        $units = $project->units()->with('povImages')->get();

        foreach ($units as $unit) {
            // Delete slice image file from storage if exists
            if ($unit->slice_image) {
                $cleaned = preg_replace('#^/?storage/#', '', $unit->slice_image);
                Storage::disk('public')->delete($cleaned);
            }
            // Delete POV images associated with unit
            $unit->images()->delete();
            $unit->delete();
        }

        return redirect()->route('admin.projects.siteplan.index', $project)
            ->with('success', 'Seluruh potongan arsiran kavling pada siteplan ini berhasil direset bersih. Anda dapat memetakan ulang dari awal.');
    }

    /**
     * Store or update lot sliced interactively from canvas/SVG
     */
    public function saveSlice(Request $request, Project $project)
    {
        $validated = $request->validate([
            'unit_id' => 'nullable|exists:units,id',
            'house_type_id' => 'required|exists:house_types,id',
            'block' => 'nullable|string|max:20',
            'number' => 'required|string|max:20',
            'x_pct' => 'required|numeric|between:0,100',
            'y_pct' => 'required|numeric|between:0,100',
            'w_pct' => 'required|numeric|between:0.5,100',
            'h_pct' => 'required|numeric|between:0.5,100',
            'facing' => 'nullable|string|max:100',
            'status' => 'required|in:available,booked,sold',
            'price' => 'nullable|numeric|min:0',
            'pov_description' => 'nullable|string',
            'pov_photo_url' => 'nullable|string',
            'slice_data_base64' => 'nullable|string', // optional cropped base64 from client canvas
        ]);

        $centerX = $validated['x_pct'] + ($validated['w_pct'] / 2);
        $centerY = $validated['y_pct'] + ($validated['h_pct'] / 2);

        $polygon = [
            [$validated['x_pct'], $validated['y_pct']],
            [$validated['x_pct'] + $validated['w_pct'], $validated['y_pct']],
            [$validated['x_pct'] + $validated['w_pct'], $validated['y_pct'] + $validated['h_pct']],
            [$validated['x_pct'], $validated['y_pct'] + $validated['h_pct']],
        ];

        // Save lot slice thumbnail if base64 provided
        $slicePath = null;
        if (!empty($validated['slice_data_base64']) && str_starts_with($validated['slice_data_base64'], 'data:image')) {
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $validated['slice_data_base64']);
            $decoded = base64_decode($base64);
            $sliceName = 'slice_' . $project->slug . '_' . Str::slug($validated['block'] . '-' . $validated['number']) . '_' . time() . '.jpg';
            Storage::disk('public')->put('slices/' . $sliceName, $decoded);
            $slicePath = 'slices/' . $sliceName;
        }

        $unitData = [
            'project_id' => $project->id,
            'house_type_id' => $validated['house_type_id'],
            'block' => $validated['block'],
            'number' => $validated['number'],
            'price' => $validated['price'] ?: 0,
            'facing' => $validated['facing'],
            'status' => $validated['status'],
            'pov_description' => $validated['pov_description'],
            'position_x' => round($centerX, 2),
            'position_y' => round($centerY, 2),
            'width_pct' => round($validated['w_pct'], 2),
            'height_pct' => round($validated['h_pct'], 2),
            'polygon_coords' => $polygon,
        ];

        if ($slicePath) {
            $unitData['slice_image'] = $slicePath;
        }

        if (!empty($validated['unit_id'])) {
            $unit = Unit::findOrFail($validated['unit_id']);
            $unit->update($unitData);
        } else {
            $unit = Unit::create($unitData);
        }

        // Handle POV Photo
        if (!empty($validated['pov_photo_url'])) {
            $unit->images()->where('category', 'pov')->delete();
            $unit->images()->create([
                'path' => $validated['pov_photo_url'],
                'caption' => 'POV Nyata dari Kavling ' . $unit->block . '-' . $unit->number,
                'is_primary' => true,
                'category' => 'pov',
                'sort_order' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kavling berhasil di-slice dan disimpan!',
            'unit' => $unit->load(['houseType', 'povImages']),
        ]);
    }
}
