<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@cafelospinos.com')],
            ['name' => 'Administrador', 'password' => env('ADMIN_PASSWORD', 'cambiar-esta-clave')],
        );

        $this->call(ProductSeeder::class);
        $this->call(PostSeeder::class);
    }
}
