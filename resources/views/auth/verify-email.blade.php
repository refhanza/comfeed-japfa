@extends('layouts.guest')

@section('title', 'Verify Email')

@section('header', 'Verify Your Email')

@section('description', 'Please check your email for verification link')

@section('content')
<div class="text-center mb-6">
    <div class="w-16 h-16 mx-auto bg-gradient-to-r from-yellow-100 to-orange-100 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-envelope-open text-2xl text-yellow-600"></i>
    </div>
    <p class="text-sm text-gray-600 mb-4">
        Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
    </p>
    <p class="text-xs text-gray-500">
        If you didn't receive the email, we will gladly send you another.
    </p>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <p class="text-green-800 text-sm font-medium">
                A new verification link has been sent to your email address.
            </p>
        </div>
    </div>
@endif

<div class="space-y-4">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div>
            <button type="submit" 
                    class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                <i class="fas fa-paper-plane mr-2"></i>
                Resend Verification Email
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <div>
            <button type="submit" 
                    class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Log Out
            </button>
        </div>
    </form>
</div>
@endsection