<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowTaskRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'uuid'],
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
