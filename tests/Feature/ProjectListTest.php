<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProjectListTest extends TestCase
{
    use RefreshDatabase;

    public function test_caches_first_page_and_can_paginate_to_second_page(): void
    {
        Project::factory()->count(15)->create();
        Cache::flush();

        $firstResponse = $this->getJson('/api/projects');

        $firstResponse->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'next_cursor',
            ]);

        $this->assertCount(10, $firstResponse->json('data'));
        $this->assertNotNull($firstResponse->json('next_cursor'));
        $this->assertTrue(Cache::has('projects:first_page'));

        $secondResponse = $this->getJson('/api/projects');
        $this->assertEquals(
            $firstResponse->json('data'),
            $secondResponse->json('data')
        );

        $nextCursor = $firstResponse->json('next_cursor');
        $secondPageResponse = $this->getJson("/api/projects?cursor={$nextCursor}");

        $secondPageResponse->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'next_cursor',
            ]);

        $this->assertCount(5, $secondPageResponse->json('data'));
    }

    public function test_returns_all_data_without_cursor_when_fits_in_one_page(): void
    {
        Project::factory()->count(5)->create();

        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'next_cursor',
            ]);

        $this->assertCount(5, $response->json('data'));
        $this->assertNull($response->json('next_cursor'));
    }

    public function test_returns_empty_list_when_no_projects(): void
    {
        $response = $this->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'next_cursor' => null,
            ]);
    }

    public function test_handles_invalid_cursor(): void
    {
        Project::factory()->count(5)->create();

        $response = $this->getJson('/api/projects?cursor=invalid_cursor_123');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cursor']);
    }
}

