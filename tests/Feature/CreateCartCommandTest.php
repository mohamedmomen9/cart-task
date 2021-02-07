<?php

use App\Order;
use App\Product;
use App\Currency;
use App\Discount;
use App\OrderItem;
use App\OfferDiscount;

it('createcart command', function () {
    $this->artisan('createCart --bill-currency=USD t-shirt t-shirt shoes jacket');
    $id = Order::max('id');
    $orderItems = OrderItem::where('order_id', $id)->get();
    $cart = [];
    $subtotal = 0;
    foreach ($orderItems as $item) {
        $product = Product::where('id', $item->item_id)->first();
        $subtotal += $product->price;
        array_push($cart, $product);
    }
    $currency = Currency::where('slug', 'USD')->first();
    $taxes = .14 * $subtotal;
    $offer = new OfferDiscount();
    $offerDiscount = $offer->calulateDiscount($cart, $currency);

    $discount = new Discount();
    $productDiscount = $discount->calulateDiscount($cart, $currency);

    $discounts = $offerDiscount + $productDiscount;
    $total = $subtotal + $taxes - $discounts;
    $order = Order::where('subtotal', $subtotal)->where('taxes', $taxes)->where('discounts', $discounts)->where('total', $total)->first();
    $order = $order->toArray();

    $this->assertArrayHasKey('id', $order);

});
