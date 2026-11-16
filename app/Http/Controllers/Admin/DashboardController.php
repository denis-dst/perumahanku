<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $totalUnits = Unit::count();
        $availableUnits = Unit::where('status', 'available')->count();
        $bookedUnits = Unit::where('status', 'booked')->count();
        $soldUnits = Unit::where('status', 'sold')->count();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();

        $recentBookings = Booking::with(['project', 'houseType'])
            ->latest()
            ->take(8)
            ->get();

        $recentProjects = Project::withCount(['units', 'houseTypes'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProjects',
            'totalUnits',
            'availableUnits',
            'bookedUnits',
            'soldUnits',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'recentBookings',
            'recentProjects'
        ));
    }
}
