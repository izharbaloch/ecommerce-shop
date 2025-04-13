<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Site\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'openHomePage'])->name('site.index');


// Route::get('product/{id}', [IndexController::class, 'openProductDetails'])->name('site.product.details');
Route::get('product/{slug}', [IndexController::class, 'openProductDetails'])->name('site.product.details');
Route::get('cart', [IndexController::class, 'openCartPage'])->name('site.cart');

Route::middleware('auth')->group(function () {
    Route::get('add_to_cart', [IndexController::class, 'addProductIntoCart'])->name('add.to.cart');
    Route::get('checkout', [IndexController::class, 'openCheckoutPage'])->name('site.checkout');
    Route::get('cart/delete{id}', [IndexController::class, 'deleteItemsFromCart'])->name('delte.cart');
    Route::get('cart/quantity/update', [IndexController::class, 'cartQuantityUpdate'])->name('cart.quantity.update');
    Route::get('cart/items/total_amount', [IndexController::class, 'calculateTotalItemsAmount'])->name('cart.items.total.amount');
    Route::post('charge', [IndexController::class, 'chargeCustomer'])->name('charge');

    // Route::get('/checkout-success', function () {
    //     return 'Payment successful!';
    // })->name('checkout.success');

    // Route::get('/checkout-cancel', function () {
    //     return 'Payment canceled!';
    // })->name('checkout.cancel');

    Route::get('/checkout-success', [IndexController::class, 'openThankuPage'])->name('checkout.success');
    Route::get('/checkout-cancel', [IndexController::class, 'openCancelPage'])->name('checkout.cancel');
});



Route::get('calculate/cart-items', [IndexController::class, 'calculateCartItems'])->name('calculate.add.to.cart');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
