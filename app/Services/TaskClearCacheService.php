<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TaskClearCacheService
{
    public function execute(int $taskId, int $projectId): void
    {
        Cache::forget("task:{$taskId}");
        Cache::forget("project:{$projectId}:progress");
        Cache::forget("project:{$projectId}:tasks:first_page");
    }
}

