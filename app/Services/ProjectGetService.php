<?php

namespace App\Services;

use App\Enums\CacheTimeEnum;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Cache;

class ProjectGetService
{
    public function __construct(
        private ProjectRepository $repository
    ) {
    }

    public function execute(int $id): ?Project
    {
        $cached = Cache::remember(
            "project:{$id}",
            CacheTimeEnum::ONE_HOUR->value,
            fn() => $this->repository->findById($id)?->toArray()
        );

        return $cached ? Project::hydrate([$cached])->first() : null;
    }
}

