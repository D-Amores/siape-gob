<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical"
    data-boxed-layout="boxed" data-card="shadow">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('modernize/assets/images/logos/favicon.png') }}">
    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('modernize/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/title.css') }}">

    <title>@yield('title')</title>

    @yield('styles')

</head>

<body data-sidebartype="mini-sidebar">
    <!-- Preloader -->
    <div class="preloader" style="display: none;">
        <img src="{{ asset('modernize/assets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid">
    </div>
    <div id="main-wrapper" class="show-sidebar">
        <!-- Sidebar Start -->
        @include('layouts._sidebar')
        <!--  Sidebar End -->
        <div class="page-wrapper">
            <!--  Header Start -->
            @include('layouts._header')
            <!--  Header End -->

            @include('layouts._sidebar-scrollbar')

            <div class="body-wrapper">
                <div class="container-fluid">
                    <!-- Page Header -->
                    <div class="page-header inventory-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="header-content">
                                    <h1 class="inventory-title">@yield('title')</h1>
                                    @hasSection('subtitle')
                                        <p class="inventory-subtitle">@yield('subtitle')</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <div class="row">

                        @yield('content')

                    </div>
                </div>
            </div>
            <script>
                function handleColorTheme(e) {
                    document.documentElement.setAttribute("data-color-theme", e);
                }
            </script>

        </div>
        <div class="dark-transparent sidebartoggler"></div>
        <script src="{{ asset('modernize/assets/js/vendor.min.js') }}"></script>
        <!-- Import Js Files -->
        <script src="{{ asset('modernize/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('modernize/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
        <script src="{{ asset('modernize/assets/js/theme/app.init.js') }}"></script>
        <script src="{{ asset('modernize/assets/js/theme/theme.js') }}"></script>
        <script src="{{ asset('modernize/assets/js/theme/app.min.js') }}"></script>
        <script src="{{ asset('modernize/assets/js/theme/sidebarmenu.js') }}"></script>

        <!-- solar icons -->
        <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
        <script src="{{ asset('modernize/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script src="{{ asset('modernize/assets/js/dashboards/dashboard2.js') }}"></script>

        @yield('scripts')
</body>

</html>
