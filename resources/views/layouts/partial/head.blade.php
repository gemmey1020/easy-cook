<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>{!! $__env->yieldContent('meta_title', 'Easy Cook') !!}</title>
    <meta name="author" content="Themeholy" />

    <meta name="description" content="{!! $__env->yieldContent('meta_description', 'Easy Cook') !!}" />
    <meta name="keywords" content="{!! $__env->yieldContent('meta_keywords', 'Easy Cook') !!}" />
    <meta name="robots" content="INDEX,FOLLOW" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="60x60" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="144x144" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="icon" type="image/png" sizes="192x192" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="icon" type="image/png" sizes="96x96" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <link rel="manifest" href="{{ url('/') }}/assets/img/favicons/favicon.ico" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="{{ url('/') }}/assets/img/favicons/ms-icon-144x144.png" />
    <meta name="theme-color" content="#ffffff" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="facebook-domain-verification" content="xoh3ywip1rjuwfz7fa5rqu3i97383r" />
    <!-- Canonical Tag -->
    <!--<link rel="canonical" href="{{ url()->current() }}">-->
    <link rel="canonical" href="https://easy-cook.vip/">

    <!--==============================
 Google Fonts
 ============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Lobster+Two:wght@400;700&family=Roboto:wght@100;300;400;500;700&family=Rubik:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <!--==============================
 All CSS File
 ============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css" />
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/fontawesome.min.css" />
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/magnific-popup.min.css" />
    <!-- Layerslider -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/layerslider.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/jquery.datetimepicker.min.css" />
    <!-- Slick Slider -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/slick.min.css" />
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/style.css" />
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/custom.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!--========= forget password =========-->
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/forget.password.css" />
    {{--    <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard/subscription-details.css') }}">
    
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Do you have a nutrition specialist?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, we have a team of expert nutritionists to help tailor your meal plan to your specific needs."
      }
    },
    {
      "@type": "Question",
      "name": "Can I upload my InBody results?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, we provide a platform for you to upload your InBody results. Our nutrition specialists use this data to create a more personalized and effective meal plan tailored to your body composition and goals."
      }
    },
    {
      "@type": "Question",
      "name": "Is the InBody test mandatory?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No, the InBody test is not mandatory, but it is highly recommended. While you can start your journey without it, uploading your results allows our team to provide a significantly more personalized nutrition plan, leading to better and faster results."
      }
    },
    {
      "@type": "Question",
      "name": "Where do you source your ingredients?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "We source our ingredients from trusted, high-quality local suppliers who share our commitment to freshness and nutrition. All ingredients are selected for their quality and nutritional value to ensure every meal supports your health goals."
      }
    },
    {
      "@type": "Question",
      "name": "I live in Zayed. Do you deliver to 6th of October?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, we deliver to all areas in Cairo and Giza, including 6th of October and Zayed. Our delivery service is in-house to ensure freshness and timely delivery."
      }
    },
    {
      "@type": "Question",
      "name": "What meals are included in the Daily Meal Box?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Each box includes 6 meals: breakfast, lunch, dinner, dessert, snack, and salad."
      }
    },
    {
      "@type": "Question",
      "name": "How can I start my EasyCook journey?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Simply choose your plan, complete our nutrition assessment, and we’ll take care of the rest!"
      }
    }
  ],
  "publisher": {
    "@type": "Organization",
    "name": "Easy Cook",
    "url": "https://easy-cook.vip/",
    "logo": {
      "@type": "ImageObject",
      "url": "https://easy-cook.vip/assets/img/logo.png"
    }
  }
}
</script>




    <script>
        // Apply theme immediately to prevent flash
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);

            function applyThemeToBody() {
                if (!document.body) {
                    setTimeout(applyThemeToBody, 0);
                    return;
                }

                document.body.setAttribute('data-theme', theme);

                // Apply class to body as soon as it's available
                if (theme === 'dark') {
                    document.body.classList.add('home-dark');
                    document.documentElement.classList.remove('temp-dark');
                } else {
                    document.body.classList.remove('home-dark');
                }
            }

            if (theme === 'dark') {
                // Add class to html temporarily, then move to body when ready
                document.documentElement.classList.add('temp-dark');
            }

            applyThemeToBody();
        })();
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    {{--    @vite(['resources/css/app.css', 'resources/js/app.js']) --}}


    <style>
        ul {
            list-style: none;
            /* إزالة النقاط لكل القوائم */
            padding: 0;
            /* إزالة المسافة البادئة الافتراضية */
            margin: 0;
            /* إزالة الهوامش */
        }

        li {
            list-style: none;
            /* إزالة النقطة السوداء */
        }

        .offcanvas__stikcy--toolbar {
            width: 100%;
            position: fixed;
            padding-bottom: 30px;
            bottom: 0;
            background: var(--white-color);
            left: 0;
            right: 0;
            z-index: 99;
            -webkit-box-shadow: 0 0 9px rgba(0, 0, 0, 0.12);
            box-shadow: 0 0 9px rgba(0, 0, 0, 0.12);
            padding: 20px 20px;
            display: none;
        }

        .offcanvas__stikcy--toolbar__btn {
            position: relative;
            text-align: center;
        }

        .offcanvas__stikcy--toolbar__btn>* {
            pointer-events: none;
        }

        .offcanvas__stikcy--toolbar__btn:hover .offcanvas__stikcy--toolbar__icon {
            background: var(--primary-color);
            color: var(--white-color);
        }

        .offcanvas__stikcy--toolbar__btn:hover .offcanvas__stikcy--toolbar__icon {
            background: var(--primary-color);
            color: var(--white-color);
        }

        .offcanvas__stikcy--toolbar__icon {
            width: 3rem;
            height: 3rem;
            line-height: 2.8rem;
            text-align: center;
            background: var(--secondary-color);
            border-radius: 50%;
            color: var(--white-color);
        }

        @media only screen and (min-width: 500px) {
            .offcanvas__stikcy--toolbar {
                padding: 10px 40px;
            }
        }

        @media only screen and (min-width: 768px) {
            .offcanvas__stikcy--toolbar {
                padding: 10px 60px;
            }
        }

        @media only screen and (max-width: 1199px) {
            .offcanvas__stikcy--toolbar {
                display: block;
            }
        }

        @media only screen and (max-width: 350px) {
            .log {
                display: none !important;
            }
        }

        .offcanvas__stikcy--toolbar__btn {
            position: relative;
            text-align: center;
        }

        .offcanvas__stikcy--toolbar__btn>* {
            pointer-events: none;
        }

        .offcanvas__stikcy--toolbar__btn:hover .offcanvas__stikcy--toolbar__icon {
            background: var(--primary-color);
            color: var(--white-color);
        }

        .offcanvas__stikcy--toolbar__icon {
            width: 3rem;
            height: 3rem;
            line-height: 2.8rem;
            text-align: center;
            background: var(--secondary-color);
            border-radius: 50%;
            color: var(--white-color);
        }

        .offcanvas__stikcy--toolbar__label {
            display: block;
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 6px;
        }







        .meal-avatar {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 8px;
            vertical-align: middle;
        }

        .meal-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        * Searchbox and Autocomplete Styling */ .searchbox {
            width: 100%;
            position: relative;
        }

        .searchbox input[type="search"] {
            width: 100%;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            border-radius: 5px;
            background: var(--white-color);
            color: var(--title-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .search-autocomplete {
            border-radius: 5px;
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            font-size: 0.9em;
            background: var(--white-color);
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            max-height: 300px;
            overflow-y: auto;
            z-index: 999;
        }

        .search-autocomplete ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .search-autocomplete li,
        .search-autocomplete a {
            display: block;
        }

        .search-autocomplete li {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s;
        }

        .search-autocomplete li:last-of-type {
            border-bottom: none;
        }

        .search-autocomplete li:hover {
            background: var(--smoke-color2);
        }

        .search-autocomplete a {
            padding: 10px 15px;
            color: var(--theme-color);
            text-decoration: none;
        }

        .search-description {
            font-size: 0.8em;
            color: var(--light-color);
            display: block;
            margin-top: 5px;
        }

        .loader,
        .load-more,
        .no-results,
        .error {
            padding: 10px 15px;
            text-align: center;
            color: var(--gray-color);
        }

        .category-header {
            padding: 8px 15px;
            background: var(--smoke-color2);
            font-weight: 600;
            color: var(--theme-color);
            border-bottom: 1px solid var(--border-color);
        }

        /* Spinner Styling */
        .spinner {
            margin: auto;
            width: 70px;
            text-align: center;
        }

        .spinner>div {
            width: 12px;
            height: 12px;
            background-color: var(--theme-color);
            border-radius: 50%;
            display: inline-block;
            animation: bouncedelay 1.4s infinite ease-in-out both;
        }

        .spinner .bounce1 {
            animation-delay: -0.32s;
        }

        .spinner .bounce2 {
            animation-delay: -0.16s;
        }

        @keyframes bouncedelay {

            0%,
            80%,
            100% {
                transform: scale(0);
            }

            40% {
                transform: scale(1);
            }
        }

        /* دعم Dark Mode */
        .dark-mode .searchbox input[type="search"],
        .dark-mode .search-autocomplete {
            background: var(--black-color2);
            color: var(--white-color);
            border-color: var(--black-color);
        }

        .dark-mode .search-autocomplete li {
            border-bottom-color: var(--black-color);
        }

        .dark-mode .search-autocomplete li:hover {
            background: var(--black-color);
        }

        .dark-mode .category-header {
            background: var(--black-color);
            color: var(--theme-color2);
        }

        .dark-mode .search-description {
            color: var(--gray-color);
        }

        .dark-mode .loader,
        .dark-mode .no-results,
        .dark-mode .error {
            color: var(--gray-color);
        }

        .search-autocomplete li:hover {
            background: var(--theme-color);
            /* لون الخلفية للهوفر */
        }

        .search-autocomplete li:hover a,
        .search-autocomplete li:hover .search-description {
            color: var(--white-color) !important;
            /* لون النص أثناء الهوفر */
        }

        .search-autocomplete .category-header:hover {
            background: transparent;
            /* عشان الـ header مياخدش لون الهوفر */
            color: var(--theme-color);
            cursor: default;
        }
    </style>

    @stack('extra-css')



<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PXS5VDTL');</script>
<!-- End Google Tag Manager -->

</head>
