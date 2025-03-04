<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function openHomePage()
    {
        
        $products = Product::all();
        return view('site.index', compact('products'));
    }

    public function openProductDetails()
    {
        return view('site.product-details');
    }

    public function openCartPage()
    {
        return view('site.cart');
    }

    public function openCheckoutPage()
    {
        return view('site.checkout');
    }

    // public function addProductIntoCart(Request $request)
    // {
    //     $product_id =  $request->product_id;

    //     $product = Product::find($product_id);

    //     if (!$product) {
    //         return response()->json([
    //             'error' => 'Unable to find this product!'
    //         ], 404);
    //     }

    //     $cart = session()->get('cart', []);
    //     $productId = $product->id;

    //     if (!$cart) {

    //         $cart = [
    //             $productId => [
    //                 'id' => $product->id,
    //                 'name' => $product->name,
    //                 'quantity' => $product->quantity,
    //                 'price' => $product->price,
    //                 'image' => $product->gallery ? $product->gallery->image : '',
    //             ]
    //         ];

    //         session()->put('cart', $cart);
    //     }

    //     return response()->json([
    //         'products' => $cart
    //     ], 201);
    // }

    public function addProductIntoCart(Request $request)
    {
        $product_id = $request->product_id;
        $product = Product::find($product_id);

        if (!$product) {
            return response()->json([
                'error' => 'Unable to find this product!'
            ], 404);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product_id])) {

            $cart[$product_id]['quantity']++;
        } else {

            $cart[$product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->gallery ? $product->gallery->image : '',
            ];
        }

        session()->put('cart', $cart);


        return response()->json([

            'message' => 'Product added to cart successfully!',
            'cart' => $cart,

        ], 201);
    }

    public function calculateCartItems()
    {
        $cart = session()->get('cart', []);
        $totalCartItems = count($cart);

        return response()->json([

            'message' => 'Product added to cart successfully!',
            'cart_total_items' => $totalCartItems,

        ], 201);
    }
}
