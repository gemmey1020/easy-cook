@extends('layouts.main-light')

@section('title', 'Blog Details')

@section('preheader')
@include("layouts.partial.preloader-light")

@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.blog-details.breadcumb")
@include("components.blog-details.blog-3")
@include("layouts.partial.sidemenu-light")
@endsection
