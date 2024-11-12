<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dui' => ['required', 'string', 'max:10'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
        ];
    }

    public function messages()
    {
        return [
            'dui.required' => 'El campo DUI es obligatorio.',
            'dui.max' => 'El DUI no puede tener más de 10 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'birthdate.required' => 'La fecha de nacimiento es obligatoria.',
            'birthdate.date' => 'Debe proporcionar una fecha válida para la fecha de nacimiento.',
        ];
    }
}
