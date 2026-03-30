@extends('layouts.main-light')

@section('title', 'Blogs')

@section('preheader')
    @include('layouts.partial.preloader-light')
    @include('layouts.partial.search-box-light')
    @include('layouts.partial.mobile-view-light')
@endsection

@section('content')
    @include('components.blogs.breadcumb')
    @include('components.blogs.blog')
    {{-- @include("components.blog.gallery") --}}
    @include("layouts.partial.sidemenu-light")

@endsection
