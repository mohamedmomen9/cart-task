<?php

namespace App;

use App\Offer;
use Illuminate\Support\Facades\Log;
use App\Interfaces\DiscountInterface;

class OfferDiscount implements DiscountInterface
{
    public function checkApplied():bool {
        return true;
    }

    public function calulateDiscount($cart, $currency) {
        $offers = Offer::select('condition', 'percent', 'price', 'name')
            ->where('active', true)
            ->whereIn('product_id', array_column($cart, 'id'))
            ->join('products', 'products.id', 'offers.product_id')
            ->get();
        $discount = 0;
        foreach($offers as $offer) {
            if(empty(array_diff(json_decode($offer->condition), array_column($cart, 'slug')))){
                $discount += $offer['price'] * $offer['percent'] * $currency->rate;
                Log::info($offer['percent'] * 100 .'% off '. $offer['name'] . ': -' . $currency->symbol . $discount);
            }
        }
        return $discount;
    }
}
