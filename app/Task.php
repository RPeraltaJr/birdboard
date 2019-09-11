<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    /*
        * Touches any belongTo relationship (in this case the parent: Project)
        * updated_at column will also update for parent Project
    */
    protected $touches = ['project']; 

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function path() {
        return "/projects/{$this->project_id}/tasks/{$this->id}";
    }

}
