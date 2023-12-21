<?php

namespace App\Http\Requests\ExamplePsp\payout;

use App\Http\Requests\FormRequest;

class Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'currency' => ['required', 'string'],
        ];
    }
}
