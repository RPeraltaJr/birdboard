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

    public function show(Project $project) {
        return view('projects.show', compact('project'));
    }

    public function store(Project $project) {

        // validate 
        $attributes = request()->validate([
            'title'         => 'required', 
            'description'   => 'required',
        ]);

        // $attributes['owner_id'] = auth()->id();
        // $project->create($attributes);
        auth()->user()->projects()->create($attributes);

        // redirect
        return redirect('/projects');
    }
}
