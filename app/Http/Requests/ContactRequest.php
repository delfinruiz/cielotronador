<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function getRedirectUrl(): string
    {
        return route('landing').'#contacto';
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mensaje' => ['required', 'string', 'min:10', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo electrónico válido.',
            'mensaje.required' => 'El campo mensaje es obligatorio.',
            'mensaje.min' => 'El mensaje debe tener al menos 10 caracteres.',
        ];
    }
}
