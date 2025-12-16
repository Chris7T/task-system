<?php

namespace App\Http\Requests;

use App\Rules\ValidCursorRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cursor' => ['sometimes', 'nullable', 'string', new ValidCursorRule()],
        ];
    }
}

