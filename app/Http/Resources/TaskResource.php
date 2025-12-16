<?php

namespace App\Http\Resources;

use App\Enums\TaskDifficulty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'completed' => $this['completed'],
            'difficulty' => $this['difficulty'],
            'difficulty_name' => TaskDifficulty::from($this['difficulty'])->name(),
        ];
    }
}

