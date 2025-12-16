<?php

namespace App\Http\Controllers;

use App\Exceptions\ProjectNotFoundException;
use App\Http\Requests\TasksListRequest;
use App\Http\Resources\TaskResource;
use App\Services\TasksListService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class TasksListController extends Controller
{
    public function __construct(
        private TasksListService $service
    ) {
    }

    #[OA\Get(
        path: "/api/projects/{id}/tasks",
        summary: "List tasks of a project",
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Project ID",
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "cursor",
                in: "query",
                required: false,
                description: "Cursor for pagination",
                schema: new OA\Schema(type: "string", nullable: true)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Tasks list returned successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Example Task"),
                                new OA\Property(property: "completed", type: "boolean", example: false),
                                new OA\Property(property: "difficulty", type: "integer", example: 2, enum: [1, 2, 3]),
                                new OA\Property(property: "difficulty_name", type: "string", example: "MEDIUM"),
                            ]
                        )),
                        new OA\Property(property: "next_cursor", type: "string", nullable: true, example: "eyJpZCI6MTB9"),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Project not found"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(TasksListRequest $request, int $id): JsonResponse
    {
        try {
            $result = $this->service->execute($id, $request->input('cursor'));

            return response()->json([
                'data' => TaskResource::collection($result['data']),
                'next_cursor' => $result['next_cursor'],
            ]);
        } catch (ProjectNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            Log::error(self::class, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'An error occurred while listing project tasks',
            ], 500);
        }
    }
}

