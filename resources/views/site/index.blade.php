@extends('layouts.site')

@section('style')
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}


    <style>
        #success_alert {
            background: rgb(148, 225, 148);
            padding: 15px;
            color: black;
            margin-bottom: 5px;
        }

        #errro_alert {
            background: red;
            padding: 15px;
            color: black;
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">

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

        <!-- End Books products grid -->

        <div class="container">
            <div class="row pt120">
                <div class="books-grid">

                    {{-- @if (session('alert_success'))
                    <div class="alert alert-primary" id="success_alert" role="alert">
                        {{ session('alert_success') }}
                    </div>
                    @endif

                    @if (session('alert_error'))
                        <div class="alert alert-primary" id="errro_alert" role="alert">
                            {{ session('alert_error') }}
                        </div>
                    @endif --}}

                    <div class="row mb30">

                        @if (count($products) > 0)
                            @foreach ($products as $product)
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mt-5" style="margin-bottom: 20px">
                                    <div class="books-item">
                                        <div class="books-item-thumb">
                                            @if ($product->gallery)
                                                <img src="{{ $product->gallery->image }}" alt="book">
                                            @endif
                                            <div class="new">New</div>
                                            <div class="sale">Sale</div>
                                            <div class="overlay overlay-books"></div>
                                        </div>

                                        <div class="books-item-info">
                                            <h5 class="books-title"><a
                                                    href="{{ route('site.product.details', $product->slug) }}">{{ $product ? $product->name : '' }}</a>
                                            </h5>

                                            <div class="books-price">{{ config('product.currency') . $product->price }}
                                            </div>
                                        </div>

                                        <a href="javascript:void(0)" id="add_to_cart_btn"
                                            class="btn btn-small btn--dark add add_to_cart_btn"
                                            data-id="{{ $product->id }}">
                                            <span class="text">Add to Cart</span>
                                            <i class="seoicon-commerce"></i>
                                        </a>

                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-danger text-center">No Products Found!</p>
                        @endif





                    </div>

                    @if (count($products) > 0)
                        <div class="row pb120">

                            {{ $products->links() }}
                            {{-- <div class="col-lg-12">

                                <nav class="navigation align-center">

                                    <a href="#" class="page-numbers bg-border-color current"><span>1</span></a>
                                    <a href="#" class="page-numbers bg-border-color"><span>2</span></a>
                                    <a href="#" class="page-numbers bg-border-color"><span>3</span></a>
                                    <a href="#" class="page-numbers bg-border-color"><span>4</span></a>
                                    <a href="#" class="page-numbers bg-border-color"><span>5</span></a>

                                    <svg class="btn-prev">
                                        <use xlink:href="#arrow-left"></use>
                                    </svg>
                                    <svg class="btn-next">
                                        <use xlink:href="#arrow-right"></use>
                                    </svg>

                                </nav>

                            </div> --}}

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>



    <script>
        $(document).ready(function() {

            $('.add_to_cart_btn').click(function() {

                var product_id = $(this).data('id')

                $.ajax({
                    url: "{{ route('add.to.cart') }}",
                    method: "GET",
                    data: {
                        product_id
                    },

                    success: function(data) {
                        if (data.message) {

                            toastr.success(data.message);
                            console.log(data.message);
                        }
                    },

                    error: function(response) {

                        // if (response.responseJSON.errors) {
                        //     alert(response);
                        // } else if
                        if (response.responseJSON.error) {
                            toastr.error(response.responseJSON.error);
                        } else {
                            toastr.error(
                                'Something went wrong, please refresh the page and try again.'
                            );
                        }

                    }
                });
            });





            function calculateCartItems() {
                $.ajax({
                    url: "{{ route('calculate.add.to.cart') }}",
                    method: "GET",
                    // data: {
                    //     product_id
                    // },

                    success: function(data) {

                        if (data.cart_total_items) {
                            $('.cart_total_items').html(data.cart_total_items)
                        }
                        console.log(data);
                        // return itemsCount;
                    },

                    error: function(response) {
                        console.log(response);

                    }
                });
            }


        });
    </script>
@endsection
