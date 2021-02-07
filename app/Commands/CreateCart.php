<?php

namespace App\Commands;

use App\Currency;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;
use App\Services\CartServices;

class CreateCart extends Command
{
    use CartServices;
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
            $total = $subtotal + $taxes - $discounts;
            Log::info("Total: " . $this->currency->symbol . $total);
            $this->storeTransaction($products, $subtotal, $taxes, $discounts, $total, $this->currency->slug);
        } catch (Expection $e) {
            Log::warning($e->getMessage());
        }
        return 0;
    }
}
