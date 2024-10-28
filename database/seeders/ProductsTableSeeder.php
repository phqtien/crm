<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Đảm bảo đã import model Product
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            Product::create([
                'name' => $faker->word,
                'price' => $faker->randomFloat(2, 1, 1000),
                'quantity' => $faker->numberBetween(1, 100),
                'description' => $faker->sentence(10),
            ]);
        }
    }
}
