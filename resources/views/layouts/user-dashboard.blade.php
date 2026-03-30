@extends('layouts.main-light')

@section('title', 'User Dashboard')

@section('preheader')
    @include('layouts.partial.preloader-light')
    @include('layouts.partial.sidemenu-light')
    @include('layouts.partial.search-box-light')
    @include('layouts.partial.mobile-view-light')
@endsection

@section('content')
<section class="my__account--section d-flex flex-column p-0">
    <div class="container-fluid h-100 px-0">
        <p class="account__welcome--text fs-5 text-center py-3 mb-0 ">
            Hello, {{ Auth::user()->first_name }} welcome to your dashboard!
        </p>
        <div class="tab-content main-content">
           
                
                    <div class="row g-0 h-100">

                        @include('components.dashboard.partials.sidebar')

                        <div class="col-md-9 col-lg-10 p-0">
                            @yield('user-dashboard-content')
                        </div>

                    </div>
                
           
        </div>
    </div>
</section>
@endsection


