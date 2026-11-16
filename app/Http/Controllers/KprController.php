<?php

namespace App\Http\Controllers;

use App\Models\KprBank;
use App\Models\Project;
use Illuminate\Http\Request;

class KprController extends Controller
{
    public function index(Request $request)
    {
        $banks = KprBank::active()->get();
        $projects = Project::active()->with('houseTypes')->get();

        // Default initial values
        $initialPrice = (float) $request->get('price', 500000000);
        $initialDpPercent = (float) $request->get('dp', 10);
        $initialTenor = (int) $request->get('tenor', 20);

        return view('kpr.index', compact('banks', 'projects', 'initialPrice', 'initialDpPercent', 'initialTenor'));
    }
}
