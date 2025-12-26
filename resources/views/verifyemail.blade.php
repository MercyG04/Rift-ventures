@extends('layouts.layoutpages')

@section('title', 'Verify Email')

@section('content')
<div class="flex flex-col items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100 text-center">
        
        <!-- Icon -->
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-primary/10">
            <i class="fas fa-envelope-open-text text-4xl text-primary"></i>
        </div>

        <!-- Heading -->
        <h2 class="mt-4 text-3xl font-extrabold text-gray-900">Check your email</h2>
        
        <!-- Message -->
        <p class="mt-4 text-sm text-gray-600 leading-relaxed">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>

        <!-- Success Message (Resent) -->
        @if (session('status') == 'verification-link-sent')
            <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative text-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="font-medium">Verification link sent!</span> Check your inbox.
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex flex-col gap-4">
            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-md text-sm font-bold text-white bg-primary hover:bg-primary/90 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                    Resend Verification Email
                </button>
            </form>

            <!-- Log Out -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-gray-900 font-medium underline">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection