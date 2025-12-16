<?php

namespace App\Services;

use App\Exceptions\ProjectNotFoundException;

class ProjectGetWithProgressService
{
    public function __construct(
        private ProjectGetService $projectGetService,
        private ProjectProgressService $progressService
    ) {
    }

    public function execute(int $id): array
    {
        $project = $this->projectGetService->execute($id);

        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        $progress = $this->progressService->execute($id);

        return [
            'id' => $project->id,
            'name' => $project->name,
            'progress' => $progress,
        ];
    }
}

