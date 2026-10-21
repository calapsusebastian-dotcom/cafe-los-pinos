<?php

namespace App\Http\Requests\Admin;

use App\Support\SiteContent;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteContentRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $imageRules = collect(SiteContent::images())
            ->flatMap(fn (array $image, string $key): array => [
                $key => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                "remove_{$key}" => ['nullable', 'boolean'],
            ])
            ->all();

        return $imageRules + collect(SiteContent::fields())
            ->map(fn (array $field, string $key): array => match (true) {
                str_ends_with($key, '_url') => ['required', 'string', 'max:600', 'regex:/^(https?:\/\/|#)/i'],
                $key === 'whatsapp_number' => ['required', 'regex:/^\d{10,15}$/'],
                in_array($key, ['shipping_cost', 'free_shipping_from'], true) => ['required', 'regex:/^\d{1,8}$/'],
                default => ['required', 'string', 'max:600'],
            })
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return collect(SiteContent::fields())
            ->map(fn (array $field): string => $field['label'])
            ->merge(collect(SiteContent::images())->map(fn (array $image): string => 'foto de '.mb_strtolower($image['label'])))
            ->all();
    }
}
