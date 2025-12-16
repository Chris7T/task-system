<?php

namespace Database\Factories;

use App\Enums\TaskDifficulty;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'completed' => false,
            'project_id' => Project::factory(),
            'difficulty' => TaskDifficulty::LOW,
        ];
    }
}

