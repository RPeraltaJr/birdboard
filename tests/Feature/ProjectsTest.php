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
}
