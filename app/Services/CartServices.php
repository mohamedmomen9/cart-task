<?php

namespace App\Services;

use App\Order;
use App\Product;
use App\Discount;
use App\OrderItem;
use App\OfferDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait CartServices
{
    protected function filterInput(array $items): array
    {
        if (count($items)) {
            //throw new Exception('Division by zero.');
            // $name = $this->anticipate('What is the items in the cart?', function ($input) {
            //     // Return auto-completion options...
            // });
        }
        $products = [];
        foreach ($items as $item) {
            $products[] = Product::where('slug', $item)->first()->toArray();
        }
        return $products;
    }

    protected function calculatePrices(array $items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += Product::where('slug', $item)
                ->first()->price * $this->currency->rate;
        }
        Log::info("Subtotal: " . $this->currency->symbol . $total);
        return $total;
    }

    protected function addTaxes($total)
    {
        $tax = $total * config('bill.tax') * $this->currency->rate;
        Log::info("Tax: " . $this->currency->symbol . $tax);
        return $tax;
    }

    protected function applyDiscouts($items)
    {
        Log::info("Dsicounts: ");
        $discount = new Discount();
        $total = $discount->calulateDiscount($items, $this->currency);
        $discount = new OfferDiscount();
        $total += $discount->calulateDiscount($items, $this->currency);
        return $total;
    }

    protected function storeTransaction($items, $subtotal, $taxes, $discounts, $total, $currency)
    {
        DB::transaction(function () use ($items, $subtotal, $taxes, $discounts, $total, $currency) {
            $order = Order::create(
                [
                    'subtotal' => $subtotal,
                    'taxes' => $taxes,
                    'discounts' => $discounts,
                    'total' => $total,
                    'currency' => $currency
                ]
            );
            foreach ($items as $item) {
                OrderItem::create(
                    [
                        'order_id' => $order->id,
                        'item_id' => $item['id']
                    ]
                );
            }
        });
    }
}
