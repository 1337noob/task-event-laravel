<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetTasksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:50'],
        ];
    }
}
