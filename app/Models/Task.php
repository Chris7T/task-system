<?php

namespace App\Models;

use App\Enums\TaskDifficulty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'completed',
        'project_id',
        'difficulty',
    ];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean',
            'difficulty' => TaskDifficulty::class,
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
