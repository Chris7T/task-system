<?php

namespace App\Services;

use App\Enums\CacheTimeEnum;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Cache;

class TaskGetService
{
    public function __construct(
        private TaskRepository $repository
    ) {
    }

    public function execute(int $id): ?Task
    {
        $task = Cache::remember(
            "task:{$id}",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->repository->findById($id)?->toArray()
        );

        return $task ? Task::hydrate([$task])->first() : null;
    }
}

