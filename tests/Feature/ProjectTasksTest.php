<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks() {

        // $this->withoutExceptionHandling();
        $this->signIn(); // create auth user

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', [
            'body' => 'Test task'
        ]);
        
        $this->get($project->path())
            ->assertSee('Test task');

    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks() {

        // $this->withoutExceptionHandling();
        $this->signIn(); // create auth user

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', [
            'body' => 'Test task'
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);

    }

    /** @test */
    public function a_task_requires_a_body() {
        
        // $this->withoutExceptionHandling();

        $this->signIn(); // create auth user

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');

    }

}
