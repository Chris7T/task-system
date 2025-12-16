<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\TaskClearCacheService;

class TaskObserver
{
    public function __construct(
        private TaskClearCacheService $clearCacheService
    ) {
    }

    public function created(Task $task): void
    {
        $this->clearCacheService->execute($task->id, $task->project_id);
    }

    public function updated(Task $task): void
    {
        $this->clearCacheService->execute($task->id, $task->project_id);
    }

    public function deleted(Task $task): void
    {
        $projectId = $task->getOriginal('project_id') ?? $task->project_id;
        $this->clearCacheService->execute($task->id, $projectId);
    }
}

