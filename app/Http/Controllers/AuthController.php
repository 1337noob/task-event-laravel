<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController
{
    #[OA\Post(
        path: "/api/auth/register",
        summary: "Register",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/AuthRegisterRequest")
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 201,
                description: "Success",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            ),
        ]
    )]
    public function register(AuthRegisterRequest $request): UserResource
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'login' => $validated['login'],
            'password' => Hash::make($validated['password']),
        ]);

        return new UserResource($user);
    }

    #[OA\Post(
        path: "/api/auth/login",
        summary: "Login",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["login", "password"],
                    properties: [
                        new OA\Property(property: "login", type: "string", example: "user"),
                        new OA\Property(property: "password", type: "string", example: "password")
                    ]
                )
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "data", properties: [
                                new OA\Property(property: "token", type: "string", example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3NjA5Nzc2NzksImV4cCI6MTc2MDk4MTI3OSwibmJmIjoxNzYwOTc3Njc5LCJqdGkiOiJwU1Z0ZDNrcEpxNDdSblpxIiwic3ViIjoiMDE5YTAwY2UtMWY3Yi03MDYxLWJkZDktNGNlZWMzYjIyODdmIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.zEn4wseQ0n67YD1Y3-F1ZaFwEmmkKodnTlZk9zM-4vU"),
                                new OA\Property(property: "type", type: "string", example: "bearer"),
                                new OA\Property(property: "expires_in", type: "integer", example: 3600),
                            ], type: "object"),
                        ]
                    )
                )
            ),
        ]
    )]
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!$token = auth()->attempt($validated)) {
            throw new AuthenticationException();
        }

        return response()->json(['data' => [
            'token' => $token,
            'type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]]);
    }

    #[OA\Post(
        path: "/api/auth/logout",
        summary: "logout",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "success"),
                        ]
                    )
                )
            ),
        ]
    )]
    public function logout(): Response
    {
        auth()->logout();

        return response()->noContent();
    }

    #[OA\Get(
        path: "/api/auth/me",
        summary: "Me",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            ),
        ]
    )]
    public function me(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
