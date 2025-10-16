<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthRegisterRequest",
    required: ["name", "login", "password"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "New user"),
        new OA\Property(property: "login", type: "string", example: "user"),
        new OA\Property(property: "password", type: "string", example: "password"),
    ]
)]
class AuthRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }
}
