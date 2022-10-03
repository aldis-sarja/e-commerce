<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Product::factory(10)->create([
            'name' => 'Autostar 66Ah',
            'price' => 5100,
            'description' => 'Autostar 66Ah 520A 12V',
            'VAT' => 21
        ]);

        \App\Models\Product::factory(1)->create([
            'name' => 'RTX4090-24G',
            'price' => 200000,
            'description' => 'Asus GeForce TUF-RTX4090-24G-GAMING',
            'VAT' => 21
        ]);

        \App\Models\Product::factory(3)->create([
            'name' => 'Armchair - Kensington Hill',
            'price' => 62000,
            'description' => 'Comfortable Push Manual Reclining Footrest Adjustable for Bedroom Living',
            'VAT' => 21
        ]);

        \App\Models\Product::factory(5)->create([
            'name' => 'Bookcase',
            'price' => 53000,
            'description' => '5-Cube Organizer Bookcase, Espresso',
            'VAT' => 21
        ]);
    }
}
