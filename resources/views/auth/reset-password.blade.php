@extends('layouts.main-light')

@section('title', 'Forgot Password')

@section('preheader')
    @include('layouts.partial.preloader-light')
    @include('layouts.partial.sidemenu-light')
    @include('layouts.partial.search-box-light')
    @include('layouts.partial.mobile-view-light')
@endsection

@section('content')
    <div class="reset-form-container">
        <h2>Reset Your Password</h2>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <input type="password" name="password" placeholder="New Password" required>

            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

            <button type="submit">Reset Password</button>
        </form>

        @if (session('status'))
            <p class="success-message">{{ session('status') }}</p>
        @endif
    </div>

@endsection
