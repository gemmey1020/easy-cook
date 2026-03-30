
@extends('layouts.master')
@section('title', 'Order Details')

@section('content')


{{-- <div id="loading-{{ $subscription->id }}" class="sales-loading d-flex justify-content-center align-items-center">--}}
{{--    <div class="spinner-border text-primary" role="status">--}}
{{--      <span class="visually-hidden"></span>--}}
{{--    </div>--}}
{{--  </div>--}}


    <div id="week-content-{{ $subscription->id }}" class="container my-5 ">



        @include("components.dashboard-admin.sales.sales-user-table", ["subscription" => $subscription])
        @include('components.dashboard-admin.sales.order-details-calender')






    </div>
@endsection
