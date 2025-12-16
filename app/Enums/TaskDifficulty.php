<?php

namespace App\Enums;

enum TaskDifficulty: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function name(): string
    {
        return match($this) {
            self::LOW => 'LOW',
            self::MEDIUM => 'MEDIUM',
            self::HIGH => 'HIGH',
        };
    }

    public function effortPoints(): int
    {
        return match($this) {
            self::LOW => 1,
            self::MEDIUM => 4,
            self::HIGH => 12,
        };
    }
}

