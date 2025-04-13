@extends('layouts.site')

@section('style')
    <style>
        .text-center {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">

        <!-- Books products grid -->


        <div class="container">

            <div class="row pt120">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="heading align-center mb60">
                        <h4 class="h1 heading-title">E-commerce tutorial</h4>

                        <p class="heading-text">Buy books, and we ship to you.

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row medium-padding120 bg-border-color">
                <div class="container">

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="order">
                                <h2 class="h1 order-title text-center">Your Order</h2>
                                @if (count($purchasedProducts) > 0)
                                    @if (session('error'))
                                        <div class="alert alert-danger" style="margin-bottom: 20px;">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <form action="{{ route('charge') }}" method="POST" class="cart-main">
                                        @csrf
                                        <table class="shop_table cart">
                                            <thead class="cart-product-wrap-title-main">
                                                <tr>
                                                    <th class="product-thumbnail">Product</th>
                                                    <th class="product-quantity">Quantity</th>
                                                    <th class="product-subtotal">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                @php
                                                    $totalAmount = 0;
                                                @endphp

                                                @foreach ($purchasedProducts as $purchasedProduct)
                                                    @php
                                                        $totalAmount +=
                                                            $purchasedProduct->quantity * $purchasedProduct->price;
                                                    @endphp
                                                    <tr class="cart_item">

                                                        <td class="product-thumbnail">

                                                            <div class="cart-product__item">
                                                                <div class="cart-product-content">
                                                                    <h5 class="cart-product-title">
                                                                        {{ $purchasedProduct->name }}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="product-quantity">

                                                            <div class="quantity">
                                                                {{ $purchasedProduct->quantity }}
                                                            </div>

                                                        </td>

                                                        <td class="product-subtotal">
                                                            <h5 class="total amount">
                                                                {{ config('product.currency') }}{{ $purchasedProduct->quantity * $purchasedProduct->price }}
                                                            </h5>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                <tr class="cart_item total">

                                                    <td class="product-thumbnail">


                                                        <div class="cart-product-content">
                                                            <h5 class="cart-product-title">Total:</h5>
                                                        </div>


                                                    </td>

                                                    <td class="product-quantity">

                                                    </td>

                                                    <td class="product-subtotal">
                                                        <h5 class="total amount">
                                                            {{ config('product.currency') }}{{ $totalAmount }}</h5>

                                                        <input type="hidden" name="cartItemsTotalAmount"
                                                            value="{{ $totalAmount }}">
                                                    </td>


                                                </tr>

                                                <tr>
                                                    <td colspan="5" class="actions">

                                                        <div class="coupon">
                                                            <input name="coupon_code" id="coupon_code"
                                                                class="email input-standard-grey" value=""
                                                                placeholder="Coupon code" type="text">

                                                        </div>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>

                                        <div class="cheque">

                                            <div class="logos">
                                                <a href="#" class="logos-item">
                                                    <img src="{{ asset('assets/site/img/visa.png') }}" alt="Visa">
                                                </a>
                                                <a href="#" class="logos-item">
                                                    <img src="{{ asset('assets/site/img/mastercard.png') }}"
                                                        alt="MasterCard">
                                                </a>
                                                <a href="#" class="logos-item">
                                                    <img src="{{ asset('assets/site/img/discover.png') }}" alt="DISCOVER">
                                                </a>
                                                <a href="#" class="logos-item">
                                                    <img src="{{ asset('assets/site/img/amex.png') }}" alt="Amex">
                                                </a>

                                                {{-- <span style="float: right;">
                                                    <form action="/your-server-side-code" method="POST">
                                                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="pk_test_6pRNASCoBOKtIshFeQd4XMUh"
                                                            data-amount="999" data-name="Stripe.com" data-description="Widget"
                                                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png" data-locale="auto" data-zip-code="true">
                                                        </script>
                                                    </form>
                                                </span> --}}

                                                <button type="submit" class="btn btn-primary"
                                                    style="background: #635BFF">Pay With Stripe</button>
                                            </div>
                                        </div>

                                    </form>
                                @else
                                    <h3 class="text-danger text-center">Product Not Available in Checkout</h3>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- End Books products grid -->



    </div>
@endsection
