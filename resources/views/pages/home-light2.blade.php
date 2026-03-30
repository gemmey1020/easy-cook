@extends('layouts.main-light')

@section('title', 'Home Page')

@section('preheader')
@include("components.light-v2.preloader-light2")
@include("components.light-v2.quick-view-light2")
@include("components.light-v2.searchbox-light2")
@include("components.light-v2.sidemenu-light2")
@include("components.light-v2.mobile-view-light2")
@endsection

@section('content')
@include("components.light-v2.hero-light2")
@include("components.light-v2.category-light2")
@include("components.light-v2.about-light2")
@include("components.light-v2.counter-light2")
@include("components.light-v2.product-light2")
@include("components.light-v2.feature-light2")
@include("components.light-v2.gallery-light2")
@include("components.light-v2.booking-light2")
@include("components.light-v2.testimonial-light2")
@include("components.light-v2.blog-light2")
@include("components.light-v2.video-light2")

@endsection
