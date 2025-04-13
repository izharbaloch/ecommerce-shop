<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\PurchasedProduct;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Coupon as StripeCoupon;




class IndexController extends Controller
{
    public function openHomePage()
    {

        $products = Product::simplePaginate(3);
        return view('site.index', compact('products'));
    }

    public function openProductDetails(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (! $product) {
            abort(404);
        }
        // return $product;
        return view('site.product-details', compact('product'));
    }

    public function openCartPage()
    {
        if (! auth()->check()) {
            return back()->with('alert_error', 'You Are Not logged in, please login and view the details!');
        }

        $cartItems = PurchasedProduct::where('user_id', auth()->id())->get();

        return view('site.cart', compact('cartItems'));
    }

    public function openCheckoutPage()
    {
        $purchasedProducts = PurchasedProduct::where('user_id', auth()->id())->get();
        return view('site.checkout', compact('purchasedProducts'));
    }



    public function addProductIntoCart(Request $request)
    {
        $quantity = $request->quantity ? $request->quantity : 1;

        // dd($quantity);
        if (! auth()->check()) {
            if ($request->quantity) {
                $request->session()->flash('alert_error', 'You are not logged in please login first to perform the action!');
                return to_route('site.index');
            } else {
                return response()->json([
                    'error' => 'You are not logged in please login first to perform the action!'
                ], 404);
            }
        }

        $product_id = $request->product_id;
        $product = Product::find($product_id);

        if (!$product) {
            return response()->json([
                'error' => 'Unable to find this product!'
            ], 404);
        }

        if ($product->quantity == 0 || $product->quantity == null) {
            return response()->json([
                'error' => 'This Product is out of stock please try another product Thanks!'
            ], 404);
        }

        if ($quantity > $product->quantity) {
            $request->session()->flash('alert_error', 'This Product is not suffient please try with lower quantity Thanks!');

            return to_route('site.index');
        }



        $user = auth()->user();

        $purchasedProduct = PurchasedProduct::where('user_id', $user->id)
            ->where('name', $product->name)
            ->where('price', $product->price)
            ->first();


        try {

            if ($purchasedProduct) {
                $purchasedProduct->update([
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $purchasedProduct->quantity + $quantity,
                    'image' => $product->gallery ? $product->gallery->image : '',
                ]);
            } else {
                PurchasedProduct::create([
                    'user_id' => $user->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->gallery ? $product->gallery->image : '',
                ]);
            }

            $product->update([
                'quantity' => $product->quantity - $quantity,
            ]);
        } catch (\Exception $ex) {
            return response()->json([

                'error' => 'please due to this error' . $ex->getMessage(),

            ], 401);
        }

        if ($request->quantity) {

            $request->session()->flash('alert_success', 'Product added to cart successfully!');
            return to_route('site.index');
        } else {
            return response()->json([
                'message' => 'Product added to cart successfully!',
            ], 201);
        }
    }

    public function deleteItemsFromCart($productId)
    {
        $product = PurchasedProduct::findOrFail($productId);
        $product->delete();

        request()->session()->flash('alert_error', 'Your Item Delete Successfully!');
        return back();
    }


    public function cartQuantityUpdate(Request $request)
    {
        $qty =  $request->qty;

        $product_id = $request->product_id;

        $product = PurchasedProduct::find($product_id);

        if (!$product_id) {
            return response()->json([
                'error' => 'This product is not found!'
            ], 404);
        }

        $product->quantity = $qty;
        $product->save(); // Save updated quantity in DB


        return response()->json([
            'product_price' => $product->price * $qty,
            'product_id' => $product->id,
        ], 201);
    }



    public function calculateTotalItemsAmount()
    {
        $products = PurchasedProduct::where('user_id', auth()->id())->get();
        $totalAmount = 0;

        foreach ($products as $product) {
            $totalAmount += $product->price * $product->quantity;
        }

        return response()->json([
            'total_amount' => config('product.currency') . $totalAmount,
        ], 200);
    }


    // public function chargeCustomer(Request $request)
    // {
    //     Stripe::setApiKey(config('services.stripe.secret_key'));

    //     try {
    //         $session = Session::create([
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //                 'price_data' => [
    //                     'currency' => 'usd',
    //                     'product_data' => [
    //                         'name' => 'Cart Purchase',
    //                     ],
    //                     'unit_amount' => $request->cartItemsTotalAmount * 100, // Convert to cents
    //                 ],
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => route('checkout.cancel'),
    //         ]);

    //         $Payment = Payment::create([
    //             'user_id' => auth()->id(),
    //             'transaction_id' => $session->id,
    //             'amount' => $request->cartItemsTotalAmount,
    //             'currancy' => env('PRODUCT_CURRENCY'),
    //             'product_name' => 'Books',
    //             'quantity' => 1,
    //             'status' => 'in-active',
    //         ]);

    //         if ($session->url) {

    //             session()->put('payment_id', $Payment ? $Payment->id : '');
    //             session()->put('transaction_id', $Payment ? $Payment->transaction_id : '');
    //             return redirect()->away($session->url);
    //         }
    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }

    public function chargeCustomer(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $couponCode = $request->coupon_code;
        $totalAmount = $request->cartItemsTotalAmount;
        $paidAmount = $totalAmount * 100;


        try {
            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Cart Purchase',
                        ],
                        'unit_amount' => $totalAmount * 100, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ];


            // ✅ Apply coupon if provided and valid
            if (!empty($couponCode)) {
                try {
                    $coupon = StripeCoupon::retrieve($couponCode);

                    if (isset($coupon->id) && !$coupon->deleted) {
                        $sessionData['discounts'] = [[
                            'coupon' => $couponCode,
                        ]];
                    } else {
                        return back()->with('error', 'The coupon code you entered is invalid or expired.');
                    }
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    // Stripe throws this when coupon is not found
                    return back()->with('error', 'The coupon code you entered is invalid.');
                } catch (\Exception $e) {
                    return back()->with('error', 'Something went wrong while validating the coupon.');
                }
            }

            // ✅ Create Stripe session
            $session = Session::create($sessionData);

            // dd($session);


            // ✅ Store Payment info in DB
            $Payment = Payment::create([
                'user_id' => auth()->id(),
                'transaction_id' => $session->id,
                'amount' => $session ? $session->amount_total / 100 : 0,
                'currancy' => env('PRODUCT_CURRENCY', 'usd'),
                'product_name' => 'Books',
                'quantity' => 1,
                'status' => 'in-active',
            ]);

            // ✅ Redirect to Stripe checkout
            if ($session->url) {
                session()->put('payment_id', $Payment->id);
                session()->put('transaction_id', $Payment->transaction_id);
                return redirect()->away($session->url);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }



    public function openThankuPage()
    {
        $transaction_id = session()->has('transaction_id') ?  session()->get('transaction_id') : '';
        if (session()->has('payment_id')) {
            $PaymentId = session()->get('payment_id');

            $Payment = Payment::find($PaymentId);

            if (! $Payment) {
                return back()->with('error', 'record not found, please contact administrator along with this payment id' . $transaction_id);
            }

            $Payment->update([
                'status' => 'processed',
            ]);
        } else {
            return back()->with('error', 'record not found, please contact administrator along with this payment id' . $transaction_id);
        }
        return view('site.thanku');
    }

    public function openCancelPage()
    {
        if (session()->has('payment_id')) {
            $PaymentId = session()->get('payment_id');

            $Payment = Payment::find($PaymentId);

            if ($Payment) {

                $Payment->delete();
            }
        }
        return view('site.cancel');
    }
}
