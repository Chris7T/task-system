<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TasksListTest extends TestCase
{
    use RefreshDatabase;

    public function test_caches_first_page_and_can_paginate_to_second_page(): void
    {
        $project = Project::factory()->create();
        Task::factory()->count(15)->create([
            'project_id' => $project->id,
        ]);
        Cache::flush();

        $firstResponse = $this->getJson("/api/projects/{$project->id}/tasks");

        $firstResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'completed',
                        'difficulty',
                        'difficulty_name',
                    ],
                ],
                'next_cursor',
            ]);

        $this->assertCount(10, $firstResponse->json('data'));
        $this->assertNotNull($firstResponse->json('next_cursor'));
        $this->assertTrue(Cache::has("project:{$project->id}:tasks:first_page"));

        $secondResponse = $this->getJson("/api/projects/{$project->id}/tasks");
        $this->assertEquals(
            $firstResponse->json('data'),
            $secondResponse->json('data')
        );

        $nextCursor = $firstResponse->json('next_cursor');
        $secondPageResponse = $this->getJson("/api/projects/{$project->id}/tasks?cursor={$nextCursor}");

        $secondPageResponse->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'completed',
                        'difficulty',
                        'difficulty_name',
                    ],
                ],
                'next_cursor',
            ]);

        $this->assertCount(5, $secondPageResponse->json('data'));
    }

    public function test_returns_all_data_without_cursor_when_fits_in_one_page(): void
    {
        $project = Project::factory()->create();
        Task::factory()->count(5)->create([
            'project_id' => $project->id,
        ]);

        $response = $this->getJson("/api/projects/{$project->id}/tasks");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'completed',
                        'difficulty',
                        'difficulty_name',
                    ],
                ],
                'next_cursor',
            ]);

        $this->assertCount(5, $response->json('data'));
        $this->assertNull($response->json('next_cursor'));
    }

    public function test_returns_empty_list_when_no_tasks(): void
    {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}/tasks");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'next_cursor' => null,
            ]);
    }

    public function test_returns_404_when_project_not_found(): void
    {
        $response = $this->getJson('/api/projects/999/tasks');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Project not found',
            ]);
    }

    public function test_handles_invalid_cursor(): void
    {
        $project = Project::factory()->create();
        Task::factory()->count(5)->create([
            'project_id' => $project->id,
        ]);

        $response = $this->getJson("/api/projects/{$project->id}/tasks?cursor=invalid_cursor_123");

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cursor']);
    }
}
