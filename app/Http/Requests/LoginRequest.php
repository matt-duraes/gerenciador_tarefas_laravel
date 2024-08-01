<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text_username' => 'required|min:3',
            'text_password' => 'required|min:5',
        ];
    }

    public function messages()
    {
        return [
            'text_username.required' => 'O campo usuário é obrigatório.',
            'text_password.required' => 'O campo senha é obrigatório.',
            'text_username.min' => 'O campo usuário deve ter no mínimo ao menos 3 caracteres.',
            'text_password.min' => 'O campo senha deve receber ao menos 8 caracteres.',
        ];
    }
}
