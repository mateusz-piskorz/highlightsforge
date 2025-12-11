<?php

namespace App\Http\Requests\Api\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequestLoginCodeVerify extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required' | 'numeric' | 'max_digits:6',
        ];
    }
}
