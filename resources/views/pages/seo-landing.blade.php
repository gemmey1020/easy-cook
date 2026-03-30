@extends('layouts.main-light')

@section('title', $area['title'])
@section('meta_title', $area['meta_title'])
@section('meta_description', $area['meta_description'])

@section('preheader')
@include("layouts.partial.preloader-light")
@include("layouts.partial.sidemenu-light")
@include("layouts.partial.search-box-light")
@include("layouts.partial.mobile-view-light")
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="breadcumb-wrapper" data-bg-src="assets/img/update_4/About_Us/Main-Banner.webp">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">{{ $service['service_en'] }} — {{ $area['area_en'] }}</h1>
            <ul class="breadcumb-menu">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>{{ $service['service_en'] }}</li>
                <li class="seo-active">{{ $area['area_en'] }}</li>
            </ul>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="seo-content-area pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="seo-article">

                    {{-- Hero Heading --}}
                    <div class="text-center mb-5">
                        <div class="seo-icon-wrap mb-3">
                            <i class="fas {{ $service['icon'] }}"></i>
                        </div>
                        <h1 class="seo-main-title">{{ $area['h1'] }}</h1>
                    </div>

                    {{-- Intro --}}
                    <p class="seo-intro lead mb-5">{{ $area['intro'] }}</p>

                    {{-- Why Section --}}
                    <div class="seo-section mb-5">
                        <h2 class="seo-section-title">
                            <i class="fas fa-check-circle me-2"></i>{{ $area['why_title'] }}
                        </h2>
                        <ul class="seo-checklist">
                            @foreach ($area['why_points'] as $point)
                                <li>
                                    <i class="fas fa-leaf seo-bullet-icon"></i>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- What Section --}}
                    <div class="seo-section mb-5">
                        <h2 class="seo-section-title">
                            <i class="fas fa-clipboard-list me-2"></i>{{ $area['what_title'] }}
                        </h2>
                        <ul class="seo-checklist">
                            @foreach ($area['what_points'] as $point)
                                <li>
                                    <i class="fas fa-utensils seo-bullet-icon"></i>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Delivery Section --}}
                    <div class="seo-section seo-delivery-box mb-5">
                        <h3 class="seo-section-title">
                            <i class="fas fa-truck me-2"></i>{{ $area['delivery_title'] }}
                        </h3>
                        <ul class="seo-checklist">
                            @foreach ($area['delivery_points'] as $point)
                                <li>
                                    <i class="fas fa-check seo-bullet-icon"></i>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Closing --}}
                    <p class="seo-closing mb-5">{{ $area['closing'] }}</p>

                    {{-- CTA --}}
                    <div class="text-center mt-5">
                        <a href="{{ url($service['cta_link']) }}" class="common-btn">
                            <i class="fas {{ $service['icon'] }} me-2"></i>{{ $service['cta_text'] }}
                        </a>
                    </div>

                </article>
            </div>
        </div>
    </div>
</div>

@endsection

@push('extra-css')
<style>
    .seo-active {
        color: #258a43 !important;
        font-weight: 800 !important;
    }

    .seo-content-area {
        background: var(--white-color, #fff);
    }

    .seo-icon-wrap {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #258a43, #2ea650);
        color: #fff;
        font-size: 2rem;
        margin: 0 auto;
    }

    .seo-main-title {
        color: #1a1a1a;
        font-weight: 700;
        font-size: 2.2rem;
        margin-top: 15px;
    }

    .seo-intro {
        font-size: 1.15rem;
        line-height: 1.9;
        color: #444;
        text-align: center;
    }

    .seo-section {
        background: #f9fafb;
        border-radius: 12px;
        padding: 30px 35px;
        border-left: 4px solid #258a43;
    }

    .seo-section-title {
        color: #1a1a1a;
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .seo-section-title i {
        color: #258a43;
    }

    .seo-checklist {
        list-style: none !important;
        padding: 0;
        margin: 0;
    }

    .seo-checklist li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 14px;
        font-size: 1.05rem;
        line-height: 1.7;
        color: #333;
    }

    .seo-bullet-icon {
        color: #258a43;
        margin-right: 12px;
        margin-top: 5px;
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .seo-delivery-box {
        background: linear-gradient(135deg, #f0faf3, #e8f5ec);
        border-left-color: #2ea650;
    }

    .seo-closing {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #444;
        text-align: center;
        font-weight: 500;
    }

    /* Dark mode support */
    [data-theme="dark"] .seo-content-area {
        background: var(--black-color2, #1a1a2e);
    }

    [data-theme="dark"] .seo-main-title,
    [data-theme="dark"] .seo-section-title {
        color: #f0f0f0;
    }

    [data-theme="dark"] .seo-intro,
    [data-theme="dark"] .seo-closing,
    [data-theme="dark"] .seo-checklist li {
        color: #ccc;
    }

    [data-theme="dark"] .seo-section {
        background: rgba(255, 255, 255, 0.05);
    }

    [data-theme="dark"] .seo-delivery-box {
        background: rgba(37, 138, 67, 0.1);
    }

    @media (max-width: 768px) {
        .seo-main-title {
            font-size: 1.6rem;
        }

        .seo-section {
            padding: 20px;
        }

        .seo-section-title {
            font-size: 1.25rem;
        }

        .seo-intro,
        .seo-closing {
            font-size: 1rem;
        }
    }
</style>
@endpush
