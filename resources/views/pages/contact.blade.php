@extends('layouts.main-light')

@section('title', 'Contact')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.contact.breadcumb")
@include("components.contact.contact")
@include("components.contact.booking")
@include("layouts.partial.sidemenu-light")
@endsection
