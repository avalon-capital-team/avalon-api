<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('platform.layouts.head', array('title'=> 'Split Assets: '))
</head>

<body class="">

    <div class="loader-wrapper">
        <div class="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="page-wrap">
        @include('platform.layouts.header')
        @yield('content')
        @include('platform.layouts.footer')
    </div>

    @yield('styles')
    @yield('scripts_default')
    @yield('scripts')
    @yield('scripts_modal_buy')
    @yield('scripts_deposit_fiat')
    @yield('scripts_strucutre')

    @include('platform.layouts.modals.receipt.index')

</body>
</html>

