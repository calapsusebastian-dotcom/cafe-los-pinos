<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the initial catalog shown on the home page.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Clásico', 'description' => 'Balance perfecto entre aroma y sabor.', 'price' => 28900],
            ['name' => 'Especial', 'description' => 'Notas más intensas y un sabor único.', 'price' => 32900],
            ['name' => 'Premium', 'description' => 'Café de altura, con notas frutales y dulces.', 'price' => 36900],
            ['name' => 'Molienda Fina', 'description' => 'Ideal para prensa francesa, moka o espresso.', 'price' => 30900],
        ];

        foreach ($products as $position => $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product + ['weight' => '500 g', 'image' => Product::FALLBACK_IMAGE, 'sort_order' => $position + 1],
            );
        }
    }
}
