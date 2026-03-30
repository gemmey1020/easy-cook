<!DOCTYPE html>
<html lang="en">

@include("layouts.partial.dashboard-admin.head")

<body class="dashboard-analytics admin-header">

    {{-- Preload Section --}}
    @include('components.dashboard-admin.main.preload')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        {{-- Sidebar Section --}}
        @include('components.dashboard-admin.main.sidebar')

        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="content-container">
                    <div class="col-left layout-top-spacing">
                        <div class="col-left-content">
                            {{-- Header Section --}}
                            <div class="header-container">
                                @include('components.dashboard-admin.main.header')
                            </div>

                            {{-- Main Content Section - This still needs to be yielded as it changes per page --}}
                            <div class="admin-data-content layout-top-spacing d-flex justify-content-center">
                                @yield('content')
                            </div>

                            {{-- Footer Section --}}
                            <!-- <div class="footer-wrapper col-xl-12">
                                <div class="footer-section f-section-1">
                                    <p class="">© 2021 <a target="_blank" href="https://designreset.com">DesignReset</a></p>
                                </div>
                                <div class="footer-section f-section-2">
                                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Core Scripts --}}
        <script src="{{ url('/') }}/assets/dashboard/assets/js/libs/jquery-3.1.1.min.js"></script>
        <script src="{{ url('/') }}/assets/dashboard/bootstrap/js/popper.min.js"></script>
        <script src="{{ url('/') }}/assets/dashboard/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{ url('/') }}/assets/dashboard/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="{{ url('/') }}/assets/dashboard/assets/js/app.js"></script>

        <script>
            $(document).ready(function() {
                App.init();
            });
        </script>
        <script src="{{ url('/') }}/assets/dashboard/assets/js/custom.js"></script>

    {{-- Page Specific Scripts --}}
    @stack('scripts')

</body>
</html>
