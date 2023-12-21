<?php

namespace App\Http\Requests\ExamplePsp\payout;

use App\Http\Requests\FormRequest;

class Headers extends FormRequest
{
    public function rules(): array
    {
        return [
            'Username' => ['required', 'string'],
            'Password' => ['required', 'string'],
        ];
    }
}
