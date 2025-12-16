<?php

namespace App\Http\Requests;

use App\Enums\TaskDifficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'integer'],
            'difficulty' => ['required', 'integer', Rule::enum(TaskDifficulty::class)],
        ];
    }
}

