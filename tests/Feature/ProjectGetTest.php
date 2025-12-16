<?php

namespace Tests\Feature;

use App\Enums\TaskDifficulty;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectGetTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_project_with_progress(): void
    {
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::HIGH,
            'completed' => true,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::MEDIUM,
            'completed' => false,
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'progress',
            ]);

        $data = $response->json();
        $this->assertEquals($project->id, $data['id']);
        $this->assertEquals($project->name, $data['name']);
        $this->assertEquals(75.0, $data['progress']);
    }

    public function test_returns_progress_zero_when_no_tasks(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $project->id,
                'name' => $project->name,
                'progress' => 0,
            ]);
    }

    public function test_returns_progress_100_when_all_tasks_completed(): void
    {
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::LOW,
            'completed' => true,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::MEDIUM,
            'completed' => true,
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200);
        $this->assertEquals(100.0, $response->json('progress'));
    }

    public function test_returns_404_when_project_not_found(): void
    {
        $response = $this->getJson('/api/projects/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Project not found',
            ]);
    }

    public function test_calculates_progress_correctly_with_different_difficulties(): void
    {
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::LOW,
            'completed' => true,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::MEDIUM,
            'completed' => true,
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::HIGH,
            'completed' => false,
        ]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertEquals($project->id, $data['id']);
        $this->assertEquals($project->name, $data['name']);
        
        $expectedProgress = round(((1 + 4) / (1 + 4 + 12)) * 100, 2);
        $this->assertEquals($expectedProgress, $data['progress']);
    }

    public function test_excludes_deleted_tasks_from_progress_calculation(): void
    {
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::HIGH,
            'completed' => true,
        ]);

        $deletedTask = Task::factory()->create([
            'project_id' => $project->id,
            'difficulty' => TaskDifficulty::HIGH,
            'completed' => false,
        ]);

        $deletedTask->delete();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertEquals(100.0, $data['progress']);
    }
}

