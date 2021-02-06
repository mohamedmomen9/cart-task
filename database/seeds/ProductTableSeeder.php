<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => "T-shirt",
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'units' => 21,
                'price' => 10.99,
                'discount' => 0,
                'slug' => 't-shirt',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'name' => "Pants",
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'units' => 400,
                'price' => 14.99,
                'discount' => 0,
                'slug' => 'pants',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'name' => "Jacket",
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'units' => 37,
                'price' => 19.99,
                'discount' => 0,
                'slug' => 'jacket',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ],
            [
                'name' => 'Shoes',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua consequat.',
                'units' => 10,
                'price' => 24.99,
                'discount' => 0.1,
                'slug' => 'shoes',
                'created_at' => null,
                'updated_at' => null,
                'deleted_at' => null
            ]
        ];
        DB::table('products')->truncate();
        DB::table('products')->insert($products);
    }
}
