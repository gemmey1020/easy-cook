@extends('layouts.main-light')

@section('title', 'Contact')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')

@include("components.contact-v2.breadcumb")
@include("components.contact-v2.map")
@include("components.contact-v2.contact")
@include("components.contact-v2.gallery")
@endsection
