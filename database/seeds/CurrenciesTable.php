<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesTableSeeder extends Seeder
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
                'slug' => 'USD',
                'name' => 'American Dollar',
                'symbol' => '$',
                'rate' => 1,
            ],
            [
                'slug' => 'EGP',
                'name' => 'Egyptian Pound',
                'symbol' => 'E£',
                'rate' => 15.68,
            ],
        ];
        DB::table('currencies')->truncate();
        DB::table('currencies')->insert($products);
    }
}
