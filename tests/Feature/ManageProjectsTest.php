<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{

    // generate fake data with WithFaker
    use WithFaker, RefreshDatabase; 

    /** @test */
    public function guests_cannot_manage_projects() {

        // $this->withoutExceptionHandling();
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login'); // tries to view all projects
        $this->get('/projects/create')->assertRedirect('login'); // tries to view Create a Project page
        $this->get($project->path())->assertRedirect('login'); // tries to view specific project
        $this->post('/projects', $project->toArray())->assertRedirect('login'); // tries to create a project

    }

    /** @test */
    public function a_user_can_create_a_project() {

        // $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create()); // authenticated user

        $this->get('/projects/create')->assertStatus(200);
 
        $attributes = [
            'title'         => $this->faker->sentence,
            'description'   => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        // should be able to post to database
        $this->assertDatabaseHas('projects', $attributes);

        // should be able to see it
        $this->get('/projects')->assertSee($attributes['title']);

    } 

    /** @test */
    public function a_user_can_view_their_project() {

        $this->be(factory('App\User')->create()); // create auth user
        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);

    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others() {

        $this->be(factory('App\User')->create()); // create auth user
        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(); 

        // try visiting the page
        $this->get($project->path())->assertStatus(403);

    }

    /** @test */
    public function a_project_requires_a_title() {

        $this->actingAs(factory('App\User')->create()); // authenticated user
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');

    }
 
    /** @test */
    public function a_project_requires_a_description() {

        $this->actingAs(factory('App\User')->create()); // authenticated user
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');

    }

}
