<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Task;

class ProjectTasksController extends Controller
{
    public function store(Project $project) {

        // * Option #1
        // if( auth()->user()->isNot($project->owner) ) {
        //     abort(403);
        // }

        // * Option #2 (after creating a ProjectPolicy.php)
        $this->authorize('update', $project);
        
        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());

    }

    public function update(Project $project, Task $task) {

        // * Option #1
        // if( auth()->user()->isNot($task->project->owner) ) {
        //     abort(403);
        // }

        // * Option #2 (after creating a ProjectPolicy.php)
        $this->authorize('update', $task->project);

        request()->validate(['body' => 'required']);

        $task->update([
            "body"      => request('body'),
            "completed" => request()->has('completed')
        ]);

        return redirect($project->path());

    }

}
