<?php

namespace MakelarisJR\Laravel2FA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Verify2FARequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'otp' => 'required|string',
        ];
    }
}