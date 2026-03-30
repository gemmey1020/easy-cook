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
        <form action="{{ route('post.password.forget') }}" method="POST">
            @csrf
            <h2>Reset Your Password</h2>
            <input type="email" name="email" placeholder="Your Email" required>
            <button type="submit">Send Reset Link</button>

            @if (session('status'))
                <p class="success-message">{{ session('status') }}</p>
            @endif
        </form>
    </div>
@endsection
