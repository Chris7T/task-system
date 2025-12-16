<?php

namespace Tests\Feature;

use App\Enums\TaskDifficulty;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task(): void
    {
        $project = Project::factory()->create();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::MEDIUM->value,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Task',
                'project_id' => $project->id,
                'difficulty' => TaskDifficulty::MEDIUM->value,
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::MEDIUM->value,
        ]);
    }

    public function test_validates_required_fields(): void
    {
        $project = Project::factory()->create();

        $this->postJson('/api/tasks', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'project_id', 'difficulty']);

        $this->postJson('/api/tasks', [
            'title' => 'Test Task',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['project_id', 'difficulty']);

        $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['difficulty']);

        $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
            'difficulty' => 99,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['difficulty']);
    }

    public function test_returns_404_when_project_not_found(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'project_id' => 999,
            'difficulty' => TaskDifficulty::LOW->value,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Project not found',
            ]);
    }
}

