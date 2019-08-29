<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectsTest extends TestCase
{

    // generate fake data with WithFaker
    use WithFaker, RefreshDatabase; 

    /** @test */
    public function a_user_can_create_a_project() {

        $this->withoutExceptionHandling();
 
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
    public function a_user_can_view_a_project() {

        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(); // given a project exists in the database

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);

    }

    /** @test */
    public function a_project_requires_a_title() {

        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_project_requires_a_description() {

        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');

    }

}
