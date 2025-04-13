<?php

use App\Models\PurchasedProduct;

if (! function_exists('countTotalCartItems')) {
    function countTotalCartItems()
    {
        $coutItems = 0;

        if (auth()->check()) {
            $coutItems = PurchasedProduct::where('user_id', auth()->id())->count();
        }

        return $coutItems;
    }
}
