@extends('layouts.main-light')

@section('title', 'Healthy Meals in Egypt – Fresh, Balanced, and Ready to Eat')
@section('meta_title', 'Healthy Meals in Egypt – Fresh, Balanced, and Ready to Eat | Easy Cook')
@section('meta_description', 'Explore healthy meals in Egypt with Easy Cook. We deliver farm-fresh, balanced meals designed by nutrition experts — weekly & monthly plans available across Cairo.')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.blog-details.breadcumb")
@include("components.blog-details.blog-5")
@endsection
