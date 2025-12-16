<?php

namespace App\Http\Controllers;

use App\Exceptions\ProjectNotFoundException;
use App\Services\ProjectGetWithProgressService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class ProjectGetController extends Controller
{
    public function __construct(
        private ProjectGetWithProgressService $service
    ) {
    }

    #[OA\Get(
        path: "/api/projects/{id}",
        summary: "Get project by ID with progress",
        tags: ["Projects"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "Project ID",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Project found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Example Project"),
                        new OA\Property(property: "progress", type: "number", format: "float", example: 75.5),
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Project not found"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(int $id): JsonResponse
    {
        try {
            $result = $this->service->execute($id);

            return response()->json($result);
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
                'error' => 'An error occurred while retrieving project',
            ], 500);
        }
    }
}

