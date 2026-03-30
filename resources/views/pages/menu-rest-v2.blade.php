@extends('layouts.main-light')

@section('title', 'Menu Restaurant')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.menu-restaurant2.breadcumb")
@include("components.menu-restaurant2.menu")
@include("components.menu-restaurant2.gallery")
@endsection
