@extends('layouts.main-light')

@section('title', 'Home Page')
@section('meta_title', 'Food Prepared Meals & Healthy Meal Plan Services | Easy Cook')
@section('meta_description', 'Order premium food prepared meals and customized meal plan programs at Easy Cook. We offer healthy meals, expert nutrition planning, and smart meal prep solutions designed to fit your lifestyle.')

@section('preheader')
@include("layouts.partial.preloader-light")

@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')

@include("components.light.hero-light")
{{-- @include("components.light.category-light") --}}
@include("components.light.about-light")
@include("components.light.counter-light")
{{-- @include("components.light.product-light") --}}
{{-- @include("components.light.offer-light") --}}
{{-- @include("components.light.menu-light") --}}
{{-- @include("components.light.booking-light") --}}
@include("components.light.plans-light")
{{-- @include("components.light.testimonial-light") --}}
@include("components.light.blog-light")
@include("components.light.faq-light")
@include("layouts.partial.sidemenu-light")
@endsection
