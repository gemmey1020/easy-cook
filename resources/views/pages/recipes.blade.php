@extends('layouts.main-light')

@section('title', 'Reciples')
@section('meta_title', 'Healthy Meals & Healthy Foods and Meals Delivery | Easy Cook')
@section('meta_description', 'Enjoy fresh healthy foods and meals prepared daily. Easy Cook provides convenient food prepared meals with balanced nutrition planning for weight loss and healthy living.')

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')
@include("components.recipes.breadcumb")
@include("components.recipes.recipe")
@endsection
