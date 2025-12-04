<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ClipRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'clip'        => 'required|file|max:102400',
            'description' => 'required|string'
        ];
    }
}
