@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('dashboard-content')

    <h1 class="text-2xl font-extrabold text-dark-text mb-6">Account Settings</h1>

    <div class="space-y-6">
        
        <!-- Update Profile Information -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete User (Optional) -->
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-gray-200">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>

@endsection
