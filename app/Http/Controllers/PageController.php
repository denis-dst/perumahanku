<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $projects = Project::active()->take(4)->get();
        return view('pages.about', compact('projects'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
