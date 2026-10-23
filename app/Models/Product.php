<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'description', 'price', 'weight', 'image', 'is_visible', 'is_sold_out', 'sort_order'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public const FALLBACK_IMAGE = 'img/productos/premium.png';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_visible' => 'boolean',
            'is_sold_out' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Public URL of the product photo. Files shipped with the site live under
     * public/img; files uploaded from the admin live on the public disk.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if (blank($this->image)) {
                return asset(self::FALLBACK_IMAGE);
            }

            return str_starts_with($this->image, 'img/')
                ? asset($this->image)
                : Storage::disk('public')->url($this->image);
        });
    }

    /**
     * @param  Builder<Product>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /**
     * @param  Builder<Product>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function hasUploadedImage(): bool
    {
        return filled($this->image) && ! str_starts_with($this->image, 'img/');
    }
}
