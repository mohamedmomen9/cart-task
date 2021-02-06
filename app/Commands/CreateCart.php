<?php

namespace App\Commands;

use Exception;
use App\Product;
use App\Currency;
use App\Discount;
use App\OfferDiscount;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class CreateCart extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'createCart
         {--bill-currency=USD : which currency do you use}
         {items* : desired items to be added to cart}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command to calculate the total price for contents of the shopping cart';

    protected $currency;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        try {
            $items = $this->argument('items');
            $this->currency = Currency::where('slug', $this->option('bill-currency'))->first();
            $products = $this->filterInput($items);
            $subtotal = $this->calculatePrices($items);
            $taxes = $this->addTaxes($subtotal);
            $discounts = $this->applyDiscouts($products);
            $total = $subtotal - ($taxes + $discounts);
            Log::info("Total: " . $this->currency->symbol . $total);
        }catch(Expection $e) {
            Log::warning($e->getMessage());
        }
        return 0;
    }

    protected function filterInput(Array $items):array {
        if(count($items)) {
             //throw new Exception('Division by zero.');
            // $name = $this->anticipate('What is the items in the cart?', function ($input) {
            //     // Return auto-completion options...
            // });
        }
        $products = [];
        foreach($items as $item) {
            $products[] = Product::where('slug', $item)->first()->toArray();
        }
        return $products;
    }

    protected function calculatePrices(Array $items) {
        $total = 0;
        foreach($items as $item) {
            $total += Product::where('slug', $item)
                            ->first()->price * $this->currency->rate;
        }
        Log::info("Subtotal: ". $this->currency->symbol . $total);
        return $total;
    }

    protected function addTaxes($total) {
        $tax = $total * config('bill.tax') * $this->currency->rate;
        Log::info("Tax: " . $this->currency->symbol . $tax);
        return $tax;
    }

    protected function applyDiscouts($items) {
        Log::info("Dsicounts: ");
        $discount = new Discount();
        $total = $discount->calulateDiscount($items, $this->currency);
        $discount = new OfferDiscount();
        $total = $discount->calulateDiscount($items, $this->currency);
        return $total;
    }
}
