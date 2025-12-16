<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function __construct(
        private Task $model
    ) {
    }

    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Task
    {
        return $this->model->find($id);
    }

    public function toggle(int $id): Task
    {
        $task = $this->findById($id);
        $task->completed = !$task->completed;
        $task->save();

        return $task;
    }

    public function delete(int $id): void
    {
        $this->findById($id)->delete();
    }

    public function cursorPaginateByProject(int $projectId, int $perPage = 15, ?string $cursor = null): \Illuminate\Contracts\Pagination\CursorPaginator
    {
        return $this->model->newQuery()
            ->where('project_id', $projectId)
            ->orderBy('id')
            ->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
    }
}

