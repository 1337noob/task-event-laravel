<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: "StoreTaskRequest",
    required: ["title"],
    properties: [
        new OA\Property(property: "title", type: "string", example: "New Task")
    ]
)]
class StoreTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
        ];
    }
}
