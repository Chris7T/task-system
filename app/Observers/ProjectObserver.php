<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\ProjectClearCacheService;

class ProjectObserver
{
    public function __construct(
        private ProjectClearCacheService $clearCacheService
    ) {
    }

    public function created(Project $project): void
    {
        $this->clearCacheService->execute($project->id);
    }

    public function updated(Project $project): void
    {
        $this->clearCacheService->execute($project->id);
    }

    public function deleted(Project $project): void
    {
        $this->clearCacheService->execute($project->id);
    }
}

