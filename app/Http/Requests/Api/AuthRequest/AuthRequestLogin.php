<?php

namespace App\Http\Requests\Api\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequestLogin extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required' | 'email',
        ];
    }
}
