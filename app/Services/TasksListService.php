<?php

namespace App\Services;

use App\Enums\CacheTimeEnum;
use App\Exceptions\ProjectNotFoundException;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Cache;

class TasksListService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private ProjectGetService $projectGetService
    ) {
    }

    public function execute(int $projectId, ?string $cursor = null): array
    {
        $project = $this->projectGetService->execute($projectId);

        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        $perPage = config('app.per_page');

        $cacheKey = $cursor === null
            ? "project:{$projectId}:tasks:first_page"
            : "project:{$projectId}:tasks:page_{$cursor}";

        $cacheTime = $cursor === null
            ? CacheTimeEnum::ONE_DAY->value
            : CacheTimeEnum::ONE_HOUR->value;

        $tasks = Cache::remember(
            $cacheKey,
            $cacheTime,
            fn() => $this->taskRepository->cursorPaginateByProject($projectId, $perPage, $cursor)->toArray()
        );

        return [
            'data' => $tasks['data'],
            'next_cursor' => $tasks['next_cursor'],
        ];
    }
}

