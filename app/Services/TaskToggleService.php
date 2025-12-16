<?php

namespace App\Services;

use App\Exceptions\TaskNotFoundException;
use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskToggleService
{
    public function __construct(
        private TaskRepository $repository,
        private TaskGetService $taskGetService
    ) {
    }

    public function execute(int $id): Task
    {
        $task = $this->taskGetService->execute($id);

        if ($task === null) {
            throw new TaskNotFoundException();
        }

        return $this->repository->toggle($id);
    }
}

