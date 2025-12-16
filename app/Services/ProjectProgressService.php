<?php

namespace App\Services;

use App\Enums\CacheTimeEnum;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Cache;

class ProjectProgressService
{
    public function __construct(
        private ProjectRepository $repository
    ) {
    }

    public function execute(int $id): float
    {
        return Cache::remember(
            "project:{$id}:progress",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->repository->calculateProgress($id)
        );
    }
}

