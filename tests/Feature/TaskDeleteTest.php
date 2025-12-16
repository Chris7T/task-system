<?php

namespace Tests\Feature;

use App\Enums\TaskDifficulty;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_task(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $project->id,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_returns_404_when_task_not_found(): void
    {
        $response = $this->deleteJson('/api/tasks/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Task not found',
            ]);
    }
}

