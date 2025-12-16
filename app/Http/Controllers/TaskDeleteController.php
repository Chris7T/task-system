<?php

namespace App\Http\Controllers;

use App\Exceptions\TaskNotFoundException;
use App\Services\TaskDeleteService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class TaskDeleteController extends Controller
{
    public function __construct(
        private TaskDeleteService $service
    ) {
    }

    #[OA\Delete(
        path: "/api/tasks/{id}",
        summary: "Delete a task (soft delete)",
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
            new OA\Response(response: 204, description: "Task deleted successfully"),
            new OA\Response(response: 404, description: "Task not found"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(int $id): JsonResponse
    {
        try {
            $this->service->execute($id);

            return response()->json(null, 204);
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
                'error' => 'An error occurred while deleting task',
            ], 500);
        }
    }
}

