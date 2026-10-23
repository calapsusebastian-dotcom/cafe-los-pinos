<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $imageRule = $this->route('product') instanceof Product ? 'nullable' : 'required';

        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:500'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'weight' => ['required', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_visible' => ['boolean'],
            'is_sold_out' => ['boolean'],
            'image' => [$imageRule, 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'price' => 'precio',
            'weight' => 'presentación',
            'sort_order' => 'orden',
            'image' => 'foto',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_visible' => $this->boolean('is_visible'),
            'is_sold_out' => $this->boolean('is_sold_out'),
        ]);
    }
}
