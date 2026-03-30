@extends('layouts.main-light')

@section('title', 'Menu Restaurant')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.kids.breadcumb")
@include("components.kids.menu")

@endsection
