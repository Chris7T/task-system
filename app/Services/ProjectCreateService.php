<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;

class ProjectCreateService
{
    public function __construct(
        private ProjectRepository $repository
    ) {
    }

    public function execute(array $data): Project
    {
        return $this->repository->create($data);
    }
}

