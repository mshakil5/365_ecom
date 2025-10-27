<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale())}}">
@php
    $company = App\Models\CompanyDetails::firstOrCreate();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    <meta property="og:type" content="website">

    @if($company->google_site_verification)
    <meta name="google-site-verification" content="{{ $company->google_site_verification }}">
    @endif

    <link href="{{ asset('images/company/' . $company->fav_icon) }}" rel="icon">

    <link rel="stylesheet" href="{{ asset('resources/frontend/css/vendor/vendor.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/frontend/css/plugins/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/frontend/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/frontend/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/frontend/css/custom.css') }}">
</head>

<body>

    @include('frontend.partials.header')

    @yield('content')
    
    @include('frontend.partials.footer')
    {{-- @include('frontend.partials.cookies') --}}
    @include('frontend.modals.add_to_cart_modal')
    @include('frontend.modals.quick_view')
    @include('frontend.modals.off_canvas')

    <script src="{{ asset('resources/frontend/js/vendor.min.js') }}"></script>
    <script src="{{ asset('resources/frontend/js/plugins.min.js') }}"></script>
    <script src="{{ asset('resources/frontend/js/main.js') }}"></script>
    <script src="{{ asset('resources/frontend/js/toastr.min.js')}}"></script>
    <script src="{{ asset('resources/frontend/js/custom.js')}}"></script>

    @yield('script')

    @include('frontend.partials.wishlist_script')
    @include('frontend.partials.add_to_cart_script')
    @include('frontend.partials.search_script')
    @include('frontend.modals.add_to_cart_modal_script')
    @include('frontend.modals.quick_view_script')
    @include('frontend.modals.add_to_wishlist_success_modal')

</body>

</html>