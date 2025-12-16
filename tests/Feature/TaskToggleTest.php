<?php

namespace Tests\Feature;

use App\Enums\TaskDifficulty;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_toggle_task_from_false_to_true(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'completed' => false,
        ]);

        $response = $this->patchJson("/api/tasks/{$task->id}/toggle");

        $response->assertStatus(200)
            ->assertJson([
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true,
        ]);
    }

    public function test_can_toggle_task_from_true_to_false(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'completed' => true,
        ]);

        $response = $this->patchJson("/api/tasks/{$task->id}/toggle");

        $response->assertStatus(200)
            ->assertJson([
                'completed' => false,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false,
        ]);
    }

    public function test_returns_404_when_task_not_found(): void
    {
        $response = $this->patchJson('/api/tasks/999/toggle');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Task not found',
            ]);
    }
}

