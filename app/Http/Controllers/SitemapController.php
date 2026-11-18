<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $projects = Project::active()->latest('updated_at')->get();
        $posts = BlogPost::published()->latest('updated_at')->get();
        $cities = Project::LAMPUNG_CITIES;

        $xml = view('sitemap', compact('projects', 'posts', 'cities'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
