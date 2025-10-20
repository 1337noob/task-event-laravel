<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetLogsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'uuid', 'max:255'],
            'event' => ['nullable', 'string', 'max:255'],
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:50'],
        ];
    }
}
