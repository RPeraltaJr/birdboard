<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectsController extends Controller
{
    public function index(Project $project) {

        // $projects = $project->all();

        // * users can only see their projects
        $projects = auth()->user()->projects; 
        return view('projects.index', compact('projects'));

    }

    public function show(Project $project) {

        // * users cannot view other projects
        // This doable due to the relations defined in both User and Projects model (hasMany and belongsTo)
        if(auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        return view('projects.show', compact('project'));
    }

    public function create() {

        return view('projects.create');
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
