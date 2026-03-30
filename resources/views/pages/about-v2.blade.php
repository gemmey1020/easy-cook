@extends('layouts.main-light')

@section('title', 'about')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.about-v2.breadcumb")
@include("components.about-v2.feature-sec1")
@include("components.about-v2.about-sec1")
@include("components.about-v2.about-sec2")
@include("components.about-v2.feature-sec2")
{{-- @include("components.about-v2.team") --}}
{{-- @include("components.about-v2.testimonial") --}}
{{-- @include("components.about-v2.gallery") --}}
@endsection
