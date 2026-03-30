@extends('layouts.main-light')

@section('title', 'User Dashboard')

@section('preheader')
    @include("layouts.partial.preloader-light")
    @include("layouts.partial.sidemenu-light")
    @include("layouts.partial.search-box-light")
    @include("layouts.partial.mobile-view-light")
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        {{-- Sidebar --}}
        @include('components.dashboard.partials.sidebar')

        {{-- Dynamic content --}}
        <div class="col-md-9 col-lg-10 p-4">
            @yield('user-content')
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Include any dashboard-only scripts here --}}
@endpush
