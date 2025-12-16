<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ProjectClearCacheService
{
    public function execute(int $projectId): void
    {
        Cache::forget("project:{$projectId}");
        Cache::forget("project:{$projectId}:progress");
        Cache::forget("project:{$projectId}:tasks:first_page");
        Cache::forget('projects:first_page');
    }
}

