@extends('layouts.main-light')

@section('title', 'Privacy Policy')

@section('preheader')
    @include('layouts.partial.preloader-light')
    @include('layouts.partial.sidemenu-light')
    @include('layouts.partial.search-box-light')
    @include('layouts.partial.mobile-view-light')
@endsection

@section('content')
    {{-- @include('components.policy.breadcumb') --}}
    @include('components.privacy.content')

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadShippingPDF() {
            const element = document.getElementById('policyContent');
            if (!element) {
                alert("policyContent not found!");
                return;
            }

            html2pdf().set({
                margin: 0.5,
                filename: 'shipping-policy-easycook.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            }).from(element).save();
        }
    </script>
@endpush
