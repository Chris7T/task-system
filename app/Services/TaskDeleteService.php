<?php

namespace App\Services;

use App\Exceptions\TaskNotFoundException;
use App\Repositories\TaskRepository;

class TaskDeleteService
{
    public function __construct(
        private TaskGetService $taskGetService,
        private TaskRepository $repository
    ) {
    }

    public function execute(int $id): void
    {
        $task = $this->taskGetService->execute($id);

        if ($task === null) {
            throw new TaskNotFoundException();
        }

        $this->repository->delete($id);
    }
}

