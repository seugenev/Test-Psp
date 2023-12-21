<?php

namespace App\Http\Requests;

interface ValidatorInterface
{
    public function rules(): array;
}
