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

    public function calulateDiscount($cart) {
        $offers = Offer::select('condition', 'percent', 'price', 'name')
            ->where('active', true)
            ->whereIn('product_id', array_column($cart, 'id'))
            ->join('products', 'products.id', 'offers.product_id')
            ->get();
        //dd(array_column($cart, 'slug'));
        $discount = 0;
        foreach($offers as $offer) {
            //dd(json_decode($offer->condition));
            if(empty(array_diff(json_decode($offer->condition), array_column($cart, 'slug')))){
                $discount += $offer['price'] * $offer['percent'];
                Log::info($offer['percent'] * 100 .'% off '. $offer['name'] . ': -$' . $discount);
            }
        }

        return $discount;
    }
}
