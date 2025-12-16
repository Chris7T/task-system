<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\Pagination\CursorPaginator;

class ProjectRepository
{
    public function __construct(
        private Project $model
    ) {
    }

    public function cursorPaginate(int $perPage = 15, ?string $cursor = null): CursorPaginator
    {
        return $this->model->newQuery()
            ->orderBy('id')
            ->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
    }

    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Project
    {
        return $this->model->find($id);
    }

    public function calculateProgress(int $id): float
    {
        $result = Task::where('project_id', $id)
            ->whereNull('deleted_at')
            ->selectRaw("
                COALESCE(SUM(CASE 
                    WHEN difficulty = 1 THEN 1
                    WHEN difficulty = 2 THEN 4
                    WHEN difficulty = 3 THEN 12
                    ELSE 0
                END), 0) as total_points,
                COALESCE(SUM(CASE 
                    WHEN difficulty = 1 THEN 1
                    WHEN difficulty = 2 THEN 4
                    WHEN difficulty = 3 THEN 12
                    ELSE 0
                END * completed), 0) as completed_points
            ")
            ->first();

        $totalPoints = (int) $result->total_points ?? 0;
        $completedPoints = (int) $result->completed_points ?? 0;

        return $totalPoints == 0 ? 0.0 : round(($completedPoints / $totalPoints) * 100, 2);
    }
}
