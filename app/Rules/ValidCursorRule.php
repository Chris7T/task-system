<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCursorRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            $fail('The :attribute must be a valid cursor.');
            return;
        }

        $json = json_decode($decoded, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $fail('The :attribute must be a valid cursor.');
            return;
        }

        if (!is_array($json) || !isset($json['id'])) {
            $fail('The :attribute must be a valid cursor.');
        }
    }
}

