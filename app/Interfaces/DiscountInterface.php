<?php

namespace App\Interfaces;

interface DiscountInterface
{
    function checkApplied():bool;
    function calulateDiscount(Array $cart, $currency);
}