@extends('layouts.main-light')

@section('title', 'Reciples')

@section('preheader')
@include("layouts.partial.preloader-light")

@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.blog.breadcumb")
@include("components.faq.faq")
@include("components.faq.booking")
@include("components.faq.gallery")
@include("layouts.partial.sidemenu-light")
@endsection
