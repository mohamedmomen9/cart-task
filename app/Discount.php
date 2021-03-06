<?php

namespace App;

use Illuminate\Support\Facades\Log;
use App\Interfaces\DiscountInterface;

class Discount implements DiscountInterface
{
    public function checkApplied():bool {
        return true;
    }

    public function calulateDiscount($cart, $currency) {
        $discount = 0;
        foreach($cart as $item) {
            $discount += $item['price'] * $item['discount'] * $currency->rate;
            if($item['discount'] > 0) {
                Log::info($item['discount'] * 100 .'% off '. $item['name'] . ': -' . $currency->symbol . $discount);
            }
        }
        return $discount;
    }
}
