<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    public function index(Project $project) {
        $projects = $project->all();
        return view('projects.index', compact('projects'));
    }

    public function store(Project $project) {
        $project->create(request(['title', 'description']));
        return redirect('/projects');
    }
}
