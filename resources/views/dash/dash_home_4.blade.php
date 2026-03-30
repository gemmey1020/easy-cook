@extends('layouts.dashboard-admin')

@section('title', 'Dashboard Admin')

@section('preload')
@include("components.dashboard-admin.main.preload")
@endsection

@section('sidebar')
@include("components.dashboard-admin.main.sidebar")
@endsection

@section('header')
@include("components.dashboard-admin.main.header")
@endsection

@section('content')
<div class="admin-data-content layout-top-spacing">
@include("components.dashboard-admin.home.content")
</div>
@endsection
