<?php

namespace App\Http\Requests\Api\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequestRegister extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required' | 'string' | 'max:250',
        ];
    }
}
