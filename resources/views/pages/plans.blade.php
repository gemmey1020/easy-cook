@extends('layouts.main-light')

@section('title', 'Menu Restaurant')
@section('meta_title', 'Customized Meal Plan & Dietary Planning Services | Easy Cook')
@section('meta_description', 'Discover personalized meal plan options with professional dietary planning and nutrition planning support. Easy Cook delivers balanced healthy meals tailored to your fitness and wellness goals.')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.plans.breadcumb")



@include("components.plans.menu")

{{--@include("components.plans.calender_js")--}}



{{-- @include("components.about.gallery") --}}
@endsection
