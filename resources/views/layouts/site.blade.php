<!DOCTYPE html>
<html lang="en">

<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Ecommerce Shop</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/fonts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/crumina-fonts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/normalize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/grid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/styles.css') }}">


    <!--Plugins styles-->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/swiper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/primary-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/site/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--Styles for RTL-->

    <!--<link rel="stylesheet" type="text/css" href="css/rtl.css">-->

    <!--External fonts-->

    @yield('style')
    <link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
</head>


<body class=" ">

    <header class="header" id="site-header">

        <div class="container">

            <div class="header-content-wrapper">

                <ul class="nav-add">
                    <li class="cart">

                        <a href="#" class="js-cart-animate">
                            <i class="seoicon-basket"></i>
                            <span class="cart-count cart_total_items">{{ countTotalCartItems() }}</span>
                        </a>

                        <div class="cart-popup-wrap">
                            <div class="popup-cart">
                                <h4 class="title-cart"><span
                                        class="cart_total_items">{{ countTotalCartItems() }}</span> products in the
                                    cart!</h4>
                                <p class="subtitle">Please make your choice.</p>
                                <div class="btn btn-small btn--dark">
                                    <span class="text"><a href="{{ route('site.cart') }}"
                                            style="text-decoration: none; color:white">view all catalog</a></span>
                                </div>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>

        </div>

    </header>

    {{-- content here --}}

    @yield('content')


    <!-- Footer -->

    <footer class="footer">
        <div class="sub-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    </div>
                </div>
            </div>
        </div>
    </footer>




    <script src="{{ asset('assets/site/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/crum-mega-menu.js') }}"></script>
    <script src="{{ asset('assets/site/js/swiper.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/theme-plugins.js') }}"></script>
    <script src="{{ asset('assets/site/js/main.js') }}"></script>
    <script src="{{ asset('assets/site/js/form-actions.js') }}"></script>

    <script src="{{ asset('assets/site/js/velocity.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/ScrollMagic.min.js') }}"></script>
    <script src="{{ asset('assets/site/js/animation.velocity.min.js') }}"></script>
    @yield('script')

    <!-- ...end JS Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        @if (session('alert_success'))
            toastr.success("{{ session('alert_success') }}");
        @endif

        @if (session('alert_error'))
            toastr.error("{{ session('alert_error') }}");
        @endif
    </script>
</body>

</html>
