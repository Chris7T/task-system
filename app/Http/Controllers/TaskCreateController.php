<?php

namespace App\Http\Controllers;

use App\Exceptions\ProjectNotFoundException;
use App\Http\Requests\TaskCreateRequest;
use App\Services\TaskCreateService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class TaskCreateController extends Controller
{
    public function __construct(
        private TaskCreateService $service
    ) {
    }

    #[OA\Post(
        path: "/api/tasks",
        summary: "Create a new task",
        tags: ["Tasks"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "project_id", "difficulty"],
                properties: [
                    new OA\Property(property: "title", type: "string", maxLength: 255, example: "New Task"),
                    new OA\Property(property: "project_id", type: "integer", example: 1),
                    new OA\Property(property: "difficulty", type: "integer", example: 2, enum: [1, 2, 3], description: "1=Low, 2=Medium, 3=High")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Task created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "title", type: "string", example: "New Task"),
                        new OA\Property(property: "project_id", type: "integer", example: 1),
                        new OA\Property(property: "difficulty", type: "integer", example: 2),
                        new OA\Property(property: "completed", type: "boolean", example: false),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Project not found"),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(TaskCreateRequest $request): JsonResponse
    {
        try {
            $result = $this->service->execute($request->validated());

            return response()->json($result, 201);
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
                'error' => 'An error occurred while creating task',
            ], 500);
        }
    }
}

