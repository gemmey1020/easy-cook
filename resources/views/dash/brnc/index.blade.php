@extends('layouts.master')

@section('title', 'BRNC')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>BRNC Dashboard</h1>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <form id="brnc-btn-form" action="{{ route('brnc.getData') }}" method="get">
                @csrf
                <input type="hidden" name="type" value="btn_track">
            </form>

            <div class="card clickable-card" data-form-id="brnc-btn-form">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    </div>
                    <h5 class="card-title">Button tracking</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <form id="brnc-reservation-form" action="{{ route('brnc.getData') }}" method="get">
                @csrf
                <input type="hidden" name="type" value="reservation">
            </form>
            <div class="card clickable-card card-link card-disabled" data-form-id="brnc-reservation-form">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <h5 class="card-title">Reservation</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <form id="brnc-newsletters-form" action="{{ route('brnc.getData') }}" method="get">
                @csrf
                <input type="hidden" name="type" value="newsletters">
            </form>
            <div class="card clickable-card card-link card-disabled" data-form-id="brnc-newsletters-form">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <h5 class="card-title">Newsletters</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <form id="brnc-contact-form" action="{{ route('brnc.getData') }}" method="get">
                @csrf
                <input type="hidden" name="type" value="contact_us">
            </form>
            <div class="card clickable-card card-link" data-form-id="brnc-contact-form">
                <div class="card-body text-center">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h5 class="card-title">Contact info</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.clickable-card').forEach(card => {
                card.addEventListener('click', function () {
                    const formId = this.dataset.formId;
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
