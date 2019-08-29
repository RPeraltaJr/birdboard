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

        // validate 
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required'
        ]);

        // persist
        $project->create($attributes);

        // redirect
        return redirect('/projects');
    }
}
