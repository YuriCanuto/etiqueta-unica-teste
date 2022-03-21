<?php

namespace Database\Seeders;

use App\Models\Product\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $products = [
            [
                'name' => 'Calça',
                'amount' => 50.25
            ],
            [
                'name' => 'Saia',
                'amount' => 20.50
            ],
            [
                'name' => 'Camisa',
                'amount' => 150.00
            ],
            [
                'name' => 'Tênis',
                'amount' => 250.99
            ],
            [
                'name' => 'Perfume',
                'amount' => 78.99
            ],
        ];

        foreach($products as $product) {
           Product::create([
                'uuid' => $faker->uuid(),
                'name' => $product['name'],
                'amount' => $product['amount'],
                'active' => true,
            ]);
        }
    }
}
