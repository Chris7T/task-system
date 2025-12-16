<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectListRequest;
use App\Services\ProjectListService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class ProjectListController extends Controller
{
    public function __construct(
        private ProjectListService $service
    ) {
    }

    #[OA\Get(
        path: "/api/projects",
        summary: "List all projects",
        tags: ["Projects"],
        parameters: [
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
                description: "Projects list returned successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Example Project"),
                            ]
                        )),
                        new OA\Property(property: "next_cursor", type: "string", nullable: true, example: "eyJpZCI6MTB9"),
                    ]
                )
            ),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(ProjectListRequest $request): JsonResponse
    {
        try {
            $result = $this->service->execute(
                $request->input('cursor')
            );

            return response()->json($result);
        } catch (Exception $e) {
            Log::error(self::class, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'An error occurred while listing projects',
            ], 500);
        }
    }
}

