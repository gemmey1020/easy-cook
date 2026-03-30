<!DOCTYPE html>
<html class="no-js" lang="en">

@include('layouts.partial.head')
{{-- @include('layouts.partial.head') --}}

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PXS5VDTL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    @yield('preheader')

    <!--============================== Header Area ==============================-->
    @include('components.light.navbar-light')

    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    @yield('content')
    @stack('extra-content')
    <!--==============================  Gallery Area ==============================-->
    {{-- @include('layouts.partial.gallery') --}}
    <!--==============================  Footer Area ==============================-->
    <!--==============================  Footer Area ==============================-->
    @include('components.light.footer-light')

    <!--******************************** Code End  Here  ******************************** -->

    <!-- Scroll To Top -->
    @include('components.light.scroll-light')
    @include('components.light.scroll-light')
    @include('components.contact.nav-icon')


    <!--============================== All Js File ============================== -->
    <!-- Jquery -->

    <script src="{{ url('/') }}/assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Slick Slider -->
    <script src="{{ url('/') }}/assets/js/slick.min.js"></script>
    <script src="{{ url('/') }}/assets/js/slick.min.js"></script>
    <!-- Layerslider -->

    <script src="{{ url('/') }}/assets/js/layerslider.transitions.js"></script>
    <script src="{{ url('/') }}/assets/js/layerslider.kreaturamedia.jquery.js"></script>
    <script src="{{ url('/') }}/assets/js/layerslider.utils.js"></script>
    <script src="{{ url('/') }}/assets/js/layerslider.transitions.js"></script>
    <script src="{{ url('/') }}/assets/js/layerslider.kreaturamedia.jquery.js"></script>
    <!-- Bootstrap -->

    <script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script>
    <!-- Magnific Popup -->

    <script src="{{ url('/') }}/assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Datepicker -->

    <script src="{{ url('/') }}/assets/js/jquery.datetimepicker.min.js"></script>
    <!-- Counter Up -->

    <script src="{{ url('/') }}/assets/js/jquery.counterup.min.js"></script>
    <!-- Isotope Filter -->

    <script src="{{ url('/') }}/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="{{ url('/') }}/assets/js/isotope.pkgd.min.js"></script>
    <!-- Main Js File -->
    <script src="{{ url('/') }}/assets/js/main.js"></script>
    <script src="{{ url('/') }}/assets/js/custom.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to apply theme from localStorage on page load
        // function applyTheme() {
        //     const theme = localStorage.getItem('theme') || 'light';
        //     console.log("Applying theme:", theme);
        //     const body = document.body;
        //
        //     // Set data-theme on <html>
        //     document.documentElement.setAttribute('data-theme', theme);
        //
        //     // Update body class based on theme
        //     if (theme === 'dark') {
        //         body.classList.add('home-dark');
        //     } else {
        //         body.classList.remove('home-dark');
        //     }
        // }

        // Toggle theme on button click
        document.querySelectorAll('#toggleTheme').forEach((toggleButton) => {
            toggleButton.addEventListener('click', () => {
                const htmlEl = document.documentElement;
                const current = htmlEl.getAttribute('data-theme') || 'light';
                const next = current === 'dark' ? 'light' : 'dark';

                htmlEl.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
            });
        });


        // Run applyTheme when the page loads
        // document.addEventListener('DOMContentLoaded', applyTheme);

    </script>

    <script>
         window.onload = function () {
        let currentHour = new Date().getHours(); // Get the current hour

        // Determine the theme based on the time
        if (currentHour >= 5 && currentHour < 20) {
            // Between 5 AM and 8 PM — light mode
            document.documentElement.setAttribute('data-theme', 'light');

        } else {
            // Between 8 PM and 5 AM — dark mode
            document.documentElement.setAttribute('data-theme', 'dark');

        }
    };
    </script>

    @include('layouts.partial.selection-storage-utils')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.EasyCookSelectionStorage?.normalizeAllSelectionStorage();
            updateCartCount();
            observeLocalStorage();
            checkWindowSize();

            $(window).resize(function() {
                checkWindowSize();
            });
        });

        // ✅ تحديث الرقم في السلة بناءً على عدد الباقات
        function updateCartCount() {
            const storedMeals = window.EasyCookSelectionStorage?.readCustomizeStore?.() || {};
            const customizeCount = Object.keys(storedMeals).length;

            const standerStore = window.EasyCookSelectionStorage?.readStandardStore?.() || {};
            const standerCount = Object.keys(standerStore).length;

            // 3) المجموع
            const totalPackages = customizeCount + standerCount;

            // 4) تحديث رقم السلة
            document.querySelectorAll(".header-button .badge")
                .forEach(badge => badge.innerText = totalPackages);
        }

        // ✅ مراقبة التعديلات على localStorage
        function observeLocalStorage() {
            window.addEventListener("storage", function(event) {
                if (["customizeMealsSave", "standerdPackages"].includes(event.key)) {
                    updateCartCount();
                }
            });

            const originalSetItem = localStorage.setItem;
            localStorage.setItem = function(key, value) {
                originalSetItem.apply(this, arguments);
                if (["customizeMealsSave", "standerdPackages"].includes(key)) {
                    window.EasyCookSelectionStorage?.normalizeAllSelectionStorage?.();
                    updateCartCount();
                }
            };
        }

        // ✅ responsive tweak
        function checkWindowSize() {
            let screenWidth = $(window).width();
            if (screenWidth >= 1199 && screenWidth <= 1300) {
                $("#media_removal").removeClass("container");
            } else {
                $("#media_removal").addClass("container");
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1, // Show 3 images in a row
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            breakpoints: {
                1200: {
                    slidesPerView: 1

                },
                992: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 1
                },
                576: {
                    slidesPerView: 1
                }

            }
        });
    </script>
    @stack('extra-js')
    @stack('scripts') <!-- this is what makes it work  -->


</body>

</html>
