<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Site\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'openHomePage'])->name('site.index');


Route::get('product/{id}', [IndexController::class, 'openProductDetails'])->name('site.product.details');
Route::get('cart', [IndexController::class, 'openCartPage'])->name('site.cart');
Route::get('add_to_cart', [IndexController::class, 'addProductIntoCart'])->name('add.to.cart');
Route::get('checkout', [IndexController::class, 'openCheckoutPage'])->name('site.checkout');

Route::get('calculate/cart-items', [IndexController::class, 'calculateCartItems'])->name('calculate.add.to.cart');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
