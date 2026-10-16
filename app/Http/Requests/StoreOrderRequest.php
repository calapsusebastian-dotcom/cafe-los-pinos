<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'regex:/^\d{7,15}$/'],
            'city' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:200'],
            'notes' => ['nullable', 'string', 'max:300'],
            'items' => ['required', 'array', 'min:1', 'max:30'],
            'items.*.id' => ['required', 'integer', Rule::exists('products', 'id')->where('is_visible', true)],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'nombre',
            'customer_phone' => 'celular',
            'city' => 'ciudad',
            'address' => 'dirección',
            'notes' => 'notas',
            'items' => 'carrito',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_phone.regex' => 'Escribe tu celular solo con números (entre 7 y 15 dígitos).',
            'items.*.id.exists' => 'Uno de los productos ya no está disponible. Revisa tu carrito.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_phone' => preg_replace('/\D/', '', (string) $this->input('customer_phone')),
        ]);
    }
}
