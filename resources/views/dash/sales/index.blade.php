@extends('layouts.master')

@section('title', 'Sales Dashboard')

@section('content')
<div class="container ">


    <h1>Sales Dashboard</h1>

    @include("components.dashboard-admin.sales.sales-table")
</div>
@endsection
