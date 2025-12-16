<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectCreateRequest;
use App\Services\ProjectCreateService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class ProjectCreateController extends Controller
{
    public function __construct(
        private ProjectCreateService $service
    ) {
    }

    #[OA\Post(
        path: "/api/projects",
        summary: "Create a new project",
        tags: ["Projects"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", maxLength: 255, example: "New Project")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Project created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "New Project"),
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(ProjectCreateRequest $request): JsonResponse
    {
        try {
            $result = $this->service->execute($request->validated());

            return response()->json($result, 201);
        } catch (Exception $e) {
            Log::error(self::class, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'An error occurred while creating project',
            ], 500);
        }
    }
}

