<!DOCTYPE html>
<html class="no-js" lang="zxx">

@include("layouts.partial.head")

<body>

    <!--============================== Header Area ==============================-->
    @include('components.light-v2.navbar-light2')

    @yield('content')
    <!--==============================  Footer Area ==============================-->
    @include('components.light-v2.footer-light2')

    <!--******************************** Code End  Here  ******************************** -->

    <!-- Scroll To Top -->
    @include("components.light-v2.scroll-light2")


    <!--============================== All Js File ============================== -->
    <!-- Jquery -->
    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Slick Slider -->
    <script src="assets/js/slick.min.js"></script>
    <!-- Layerslider -->
    <script src="assets/js/layerslider.utils.js"></script>
    <script src="assets/js/layerslider.transitions.js"></script>
    <script src="assets/js/layerslider.kreaturamedia.jquery.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Magnific Popup -->
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Datepicker -->
    <script src="assets/js/jquery.datetimepicker.min.js"></script>
    <!-- Counter Up -->
    <script src="assets/js/jquery.counterup.min.js"></script>
    <!-- Isotope Filter -->
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <!-- Main Js File -->
    <script src="assets/js/main.js"></script>
</body>

</html>
