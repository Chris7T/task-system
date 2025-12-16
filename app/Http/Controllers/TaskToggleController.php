<?php

namespace App\Http\Controllers;

use App\Exceptions\TaskNotFoundException;
use App\Services\TaskToggleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class TaskToggleController extends Controller
{
    public function __construct(
        private TaskToggleService $service
    ) {
    }

    #[OA\Patch(
        path: "/api/tasks/{id}/toggle",
        summary: "Toggle task completion status",
        tags: ["Tasks"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Task ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Task status updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "title", type: "string", example: "Example Task"),
                        new OA\Property(property: "completed", type: "boolean", example: true),
                        new OA\Property(property: "project_id", type: "integer", example: 1),
                        new OA\Property(property: "difficulty", type: "integer", example: 2),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Task not found"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(int $id): JsonResponse
    {
        try {
            $result = $this->service->execute($id);

            return response()->json($result);
        } catch (TaskNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            Log::error(self::class, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'An error occurred while toggling task',
            ], 500);
        }
    }
}

