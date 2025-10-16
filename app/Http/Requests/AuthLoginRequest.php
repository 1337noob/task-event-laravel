<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthLoginRequest",
    required: ["login", "password"],
    properties: [
        new OA\Property(property: "login", type: "string", example: "user"),
        new OA\Property(property: "password", type: "string", example: "password"),
    ]
)]
class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
