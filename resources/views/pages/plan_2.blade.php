@extends('layouts.main-light')

@section('title', 'Menu Restaurant')

@section('preheader')
    @include("layouts.partial.preloader-light")
    @include("layouts.partial.sidemenu-light")
    @include("layouts.partial.search-box-light")
    @include("layouts.partial.mobile-view-light")
@endsection

@section('content')
    @include("components.plans.breadcumb")



    @include("components.plans.menu_2")

    {{--@include("components.plans.calender_js")--}}


    {{-- @include("components.about.gallery") --}}
@endsection
