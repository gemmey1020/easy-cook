@php
    $success = session('success');
    $orderId = session('order_id');
@endphp



@extends('layouts.main-light')

@section('title', 'thank you')

@section('preheader')
    @include("layouts.partial.preloader-light")
    @include("layouts.partial.sidemenu-light")
    @include("layouts.partial.search-box-light")
    @include("layouts.partial.mobile-view-light")
@endsection

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center gap-5" style="height: 55vh; ">
    <h2>✅ Payment Successful!</h2>
    <p>Thank you for your payment. You will be redirected to your profile shortly...</p>

    </div>
    <script>
        setTimeout(function () {
            window.location.href = "{{ route('user-dashboard') }}";
        }, 5000);
    </script>



@endsection
