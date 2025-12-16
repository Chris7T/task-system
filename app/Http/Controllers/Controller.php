<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Task System API",
    description: "API for managing projects and tasks with weighted progress calculation"
)]
#[OA\Server(
    url: "http://localhost:8080/api",
    description: "Development server"
)]
#[OA\Tag(name: "Projects", description: "Project management endpoints")]
#[OA\Tag(name: "Tasks", description: "Task management endpoints")]
abstract class Controller
{
    //
}
