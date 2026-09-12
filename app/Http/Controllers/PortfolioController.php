<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'featuredProject' => config('projects.onderwijsformulier'),
        ]);
    }

    public function index(): Response
    {
        return Inertia::render('Work/Index', [
            'projects' => array_values(config()->array('projects')),
        ]);
    }

    public function show(string $slug): Response
    {
        $project = config()->array('projects')[$slug] ?? null;

        abort_unless(is_array($project), 404);

        return Inertia::render('Work/Show', ['project' => $project]);
    }
}
