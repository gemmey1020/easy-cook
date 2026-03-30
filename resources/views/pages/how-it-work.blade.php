@extends('layouts.main-light')

@section('title', 'Reciples')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.how-it-work.breadcumb")
@include("components.how-it-work.how-it-work")
@endsection
