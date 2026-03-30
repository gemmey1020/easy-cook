@extends('layouts.main-dark')

@section('title', 'Home Page')

@section('preheader')
@include("components.dark.preloader-dark")
@include("components.dark.quick-view-dark")
@include("components.dark.sidemenu-dark")
@include("components.dark.search-box-dark")
@include("components.dark.mobile-view-dark")
@endsection

@section('content')
@include("components.dark.hero-dark")
@include("components.dark.category-dark")
@include("components.dark.about-dark")
@include("components.dark.counter-dark")
@include("components.dark.product-dark")
@include("components.dark.offer-dark")
@include("components.dark.menu-dark")
@include("components.dark.booking-dark")
@include("components.dark.testimonial-dark")
@include("components.dark.blog-dark")
@endsection
