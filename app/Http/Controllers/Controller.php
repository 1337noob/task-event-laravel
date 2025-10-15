<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "Tasks API",
    title: "Tasks API"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Tasks API"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
abstract class Controller
{
    //
}
