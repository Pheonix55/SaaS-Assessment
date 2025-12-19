<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- site metas -->
    <title>@yield('title', 'SaaS App')</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- site icon -->
    <link rel="icon" href="{{ asset('images/fevicon.png') }}" type="image/png" />

    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />

    <!-- site css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <!-- responsive css -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />

    <!-- color css -->
    <link rel="stylesheet" href="{{ asset('css/color_2.css') }}" />

    <!-- select bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}" />

    <!-- scrollbar css -->
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}" />

    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />

    <!-- calendar file css -->
    <link rel="stylesheet" href="{{ asset('css/semantic.min.css') }}" />

    @yield('styles')
</head>

<body class="inner_page">
    <div class="full_container">
        @yield('content')
    </div>

    {{-- Global Loader --}}
    @include('partials.loader')

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- wow animation -->
    <script src="{{ asset('js/animate.js') }}"></script>

    <!-- select country -->
    <script src="{{ asset('js/bootstrap-select.js') }}"></script>

    <!-- nice scrollbar -->
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    <script>
        if (document.getElementById('sidebar')) {
            new PerfectScrollbar('#sidebar');
        }
    </script>

    <!-- custom js -->
    <script src="{{ asset('js/custom.js') }}"></script>

    @yield('scripts')
</body>

</html>
