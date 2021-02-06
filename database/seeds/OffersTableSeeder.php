<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
                'product_id' => 3,
                'percent' => 0.5,
                'condition' => json_encode(['t-shirt', 't-shirt', 'jacket']),
                'active' => true
            ];
        DB::table('offers')->truncate();
        DB::table('offers')->insert($products);
    }
}
