@extends('layouts.main-light')

@section('title', 'about')
@section('meta_title', 'About Easy Cook | Food Prepared Meals & Healthy Meal Plan Experts')
@section('meta_description', 'Learn more about Easy Cook, your trusted provider of food prepared meals, customized meal plan programs, and professional nutrition planning. We deliver healthy meals and smart meal prep solutions designed to support balanced dietary planning and healthy living.')


@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection


@section('content')
@include("components.about.breadcumb")
@include("components.about.about")
@include("components.about.video")
@include("components.about.delivery-team")
@include("components.about.kids-banner")
@include("components.about.feature")
@include("components.about.commitment")
@include("components.about.community")
{{-- @include("components.about.testimonial") --}}
{{-- @include("components.about.team") --}}
{{-- @include("components.about.brand") --}}
{{-- @include("components.about.gallery") --}}
@endsection
