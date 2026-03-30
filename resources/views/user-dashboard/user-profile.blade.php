@extends('layouts.user-dashboard')

@section('title', 'Dashboard')

@section('user-dashboard-content')


    <div class="tab-pane h-100" id="profile">
        <div class="p-4 h-100 d-flex flex-column">

            {{-- ✅ Top Row: Profile Completion + Edit Button --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Profile Completion</h4>
                    <small class="text-muted">Complete your profile to get the most out of
                        Easy Cook.</small>
                </div>
                <!-- <button type="button" class="btn btn-outline-primary" id="editProfileBtn">
                                                    Edit Profile
                                                </button> -->

                <button id="editProfileBtn" class="Btn">Edit
                    <svg class="svg" viewBox="0 0 512 512">
                        <path
                            d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- ✅ Progress Bar --}}
            <div class="mb-4">
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" id="profileProgressBar" style="width: 0%;">
                        <span id="profileProgressText">🟢 80% Complete</span>

                    </div>
                </div>
            </div>

            {{-- ✅ Profile Info --}}
            <form class="row" action="{{ route('user-dashboard.profile.update', Auth::user()->id) }}" method="POST">
                @csrf
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                        id="firstName" placeholder="First Name" name="first_name"
                        value="{{ old('first_name', Auth::user()->first_name) }}" disabled
                        >

                    @error('first_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                        id="lastName" placeholder="Last Name" name="last_name"
                        value="{{ old('last_name', Auth::user()->last_name) }}" disabled>

                    @error('last_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" placeholder="Email Address" name="email"
                        value="{{ old('email', Auth::user()->email) }}" disabled>

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                        id="phone" placeholder="Phone number" name="phone"
                        value="{{ old('phone', Auth::user()->phone) }}" disabled>

                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                    {{--                <div class="col-12 mb-3">--}}
                    {{--                    <input type="text" class="form-control" id="company" placeholder="Your Company Name" name="company_name"--}}
                    {{--                        value="{{ Auth::user()->company_name ?? '' }}" disabled>--}}
                    {{--                </div>--}}


                {{-- ✅ Save Button (hidden initially) --}}
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success d-none" id="saveProfileBtn">Save Changes
                    </button>

                </div>

            </form>

            {{-- ✅ Addresses Section --}}
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h4 class="mb-0">Addresses</h4>
                <button type="button" class="btn btn-success" id="addAddressBtn"
                    data-url="{{ route('addresses.store') }}">Add a
                    new address
                </button>
            </div>

            <div id="addressList" class="flex-grow-1 overflow-auto">
                @include('partials.addresses')
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/dashboard/profile.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/userPanelAdresses.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const first = document.getElementById('firstName');
            const last = document.getElementById('lastName');
            const phone = document.getElementById('phone');

            if (first) {
                first.addEventListener('input', function () {
                    this.value = this.value.replace(/[^A-Za-z\s]/g, '');
                });
            }

            if (last) {
                last.addEventListener('input', function () {
                    this.value = this.value.replace(/[^A-Za-z\s]/g, '');
                });
            }

            if (phone) {
                phone.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '');
                });
            }
        });
    </script>

@endpush
