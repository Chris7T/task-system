<?php

namespace App\Services;

use App\Exceptions\ProjectNotFoundException;
use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskCreateService
{
    public function __construct(
        private TaskRepository $repository,
        private ProjectGetService $projectGetService
    ) {
    }

    public function execute(array $data): Task
    {
        $project = $this->projectGetService->execute($data['project_id']);

        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        return $this->repository->create($data);
    }
}

