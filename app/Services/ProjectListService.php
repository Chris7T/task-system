<?php

namespace App\Services;

use App\Enums\CacheTimeEnum;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Cache;

class ProjectListService
{
    public function __construct(
        private ProjectRepository $repository
    ) {}

    public function execute(?string $cursor = null): array
    {
        $perPage = config('app.per_page');
        
        $cacheKey = $cursor === null 
            ? 'projects:first_page' 
            : "projects:page_{$cursor}";
        
        $cacheTime = $cursor === null 
            ? CacheTimeEnum::ONE_DAY->value 
            : CacheTimeEnum::ONE_HOUR->value;

        $projects = Cache::remember(
            $cacheKey,
            $cacheTime,
            fn() => $this->repository->cursorPaginate($perPage, $cursor)->toArray()
        );

        return [
            'data' => $projects['data'],
            'next_cursor' => $projects['next_cursor'],
        ];
    }
}
