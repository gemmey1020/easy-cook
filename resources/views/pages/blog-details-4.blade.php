@extends('layouts.main-light')

@section('title', 'Food Prepared Meals in Egypt – Fresh & Ready to Eat Daily')
@section('meta_title', 'Food Prepared Meals in Egypt – Fresh & Ready to Eat Daily | Easy Cook')
@section('meta_description', 'Discover fresh, healthy food prepared meals in Egypt. Easy Cook offers ready-to-eat meals, weekly & monthly subscriptions, and expert nutrition planning delivered across Cairo.')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.blog-details.breadcumb")
@include("components.blog-details.blog-4")
@endsection
