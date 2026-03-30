@extends('layouts.main-light')

@section('title', 'Menu Fast Food')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.menu-fast-food.breadcumb")
@include("components.menu-fast-food.menu")
@include("components.menu-fast-food.gallery")
@endsection
