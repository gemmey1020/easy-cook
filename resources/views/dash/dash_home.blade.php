@extends('layouts.master')

@section('title', 'Dashboard')

@include('dash.discount-ops.partials.styles')

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
