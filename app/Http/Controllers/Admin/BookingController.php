<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Project;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['project', 'houseType'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $projects = Project::all();

        return view('admin.bookings.index', compact('bookings', 'projects'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['project', 'houseType']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,done,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update($validated);

        return redirect()->back()->with('success', 'Status booking survei berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Data booking survei berhasil dihapus.');
    }
}
