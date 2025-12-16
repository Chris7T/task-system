<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_project(): void
    {
        $response = $this->postJson('/api/projects', [
            'name' => 'Test Project',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test Project',
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
        ]);
    }

    public function test_validates_name_is_required(): void
    {
        $response = $this->postJson('/api/projects', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validates_name_is_string(): void
    {
        $response = $this->postJson('/api/projects', [
            'name' => 123,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validates_name_max_length(): void
    {
        $response = $this->postJson('/api/projects', [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}

