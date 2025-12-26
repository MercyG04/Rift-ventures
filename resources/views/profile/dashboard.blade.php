@extends('layouts.layoutpages')

@section('title', 'My Account')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">My Account</h1>
            <p class="text-gray-500 mt-1">Manage your profile, view bookings, and check your wishlist.</p>
        </div>
        <!-- success status specifically for password updates/profile changes if using standard Laravel keys -->
        @if (session('status') === 'password-updated' || session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mt-4 md:mt-0 px-4 py-2 bg-green-100 text-green-800 text-sm font-bold rounded-lg shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-2"></i> Changes saved successfully.
            </div>
        @endif
    </div>

    <!-- MAIN LAYOUT -->
    <div class="flex flex-col lg:flex-row gap-8" x-data="{ activeTab: 'profile' }">

        <!-- SIDEBAR -->
        <aside class="w-full lg:w-72 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                <div class="p-8 bg-gray-50 border-b border-gray-100 text-center">
                    <!-- Using Auth::user() ensures this works without controller passing $user -->
                    <img class="h-24 w-24 rounded-full object-cover mx-auto border-4 border-white shadow-md mb-4" 
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=7a4d78&color=fff&size=128" 
                         alt="{{ Auth::user()->name }}">
                    <h3 class="font-bold text-gray-900 text-lg">{{ Str::limit(Auth::user()->name, 20) }}</h3>
                    <p class="text-sm text-gray-500">{{ Str::limit(Auth::user()->email, 25) }}</p>
                </div>

                <nav class="p-3 space-y-2">
                    <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'" class="w-full flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group">
                        <i :class="activeTab === 'profile' ? 'text-white' : 'text-gray-400 group-hover:text-primary'" class="fas fa-user-circle w-6 text-lg mr-2 transition-colors"></i> Profile Settings
                    </button>
                    <button @click="activeTab = 'bookings'" :class="activeTab === 'bookings' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'" class="w-full flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group">
                        <i :class="activeTab === 'bookings' ? 'text-white' : 'text-gray-400 group-hover:text-primary'" class="fas fa-suitcase-rolling w-6 text-lg mr-2 transition-colors"></i> Booking History
                    </button>
                    <button @click="activeTab = 'wishlist'" :class="activeTab === 'wishlist' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'" class="w-full flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-all duration-200 group">
                        <i :class="activeTab === 'wishlist' ? 'text-white' : 'text-gray-400 group-hover:text-primary'" class="fas fa-heart w-6 text-lg mr-2 transition-colors"></i> My Wishlist
                    </button>
                    
                    <div class="pt-4 mt-2 border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-6 text-lg mr-2"></i> Log Out
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- CONTENT -->
        <div class="flex-1">
            
            <!-- TAB: PROFILE -->
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300">
                
                <!-- Info Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-id-card text-primary mr-3"></i> Profile Information
                    </h2>
                    
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('patch')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition duration-200" required>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition duration-200" placeholder="+254...">
                                @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition duration-200" required>
                                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-bold shadow-md hover:bg-purple-700 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100 flex items-center">
                        <i class="fas fa-lock text-primary mr-3"></i> Security & Password
                    </h2>
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('put')
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                            @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">New Password</label>
                                <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-8">
                             <!-- Forgot Password Link -->
                             @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-purple-700 hover:underline">
                                    Forgot your password?
                                </a>
                            @else
                                <span></span>
                            @endif

                            <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-lg font-bold shadow-md hover:bg-black transition-all transform hover:-translate-y-0.5">
                                Update Password
                            </button>
                        </div>
                    </form>
                    
                </div>
                <div class="bg-red-50 rounded-xl shadow-sm border border-red-100 p-6">

                    <h2 class="text-lg font-bold text-red-700 mb-2">Delete Account</h2>

                    <p class="text-sm text-red-600 mb-4">Once deleted, your account is gone forever.</p>

                    <button class="bg-red-600 text-white px-6 py-2 rounded-full font-bold text-sm shadow opacity-50 cursor-not-allowed">Delete Account</button>

                </div>
            </div>

            <!-- TAB: BOOKINGS -->
            <div x-show="activeTab === 'bookings'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                
                {{-- FETCHING BOOKINGS DIRECTLY FROM RELATIONSHIP --}}
                @php
                    $bookings = Auth::user()->bookings()->with('safariPackage')->latest()->get();
                @endphp

                @forelse($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4 hover:shadow-md transition duration-300">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                            
                            <!-- Info -->
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 text-xs font-bold uppercase rounded-full 
                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : 
                                          ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $booking->status }}
                                    </span>
                                    <span class="text-sm text-gray-500">Booked on {{ $booking->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-primary mb-1">
                                    {{ $booking->safariPackage->title ?? 'Custom Package' }}
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    <i class="fas fa-calendar-alt mr-1"></i> Check-in: {{ \Carbon\Carbon::parse($booking->booking_date)->format('D, M d Y') }}
                                    <span class="mx-2 text-gray-300">|</span>
                                    <i class="fas fa-users mr-1"></i> {{ $booking->num_travelers }} Travelers
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="flex items-center gap-4">
                                <div class="text-right hidden md:block">
                                    <p class="text-xs text-gray-400 uppercase font-bold">Total Cost</p>
                                    <p class="text-lg font-extrabold text-gray-900">
                                        {{ $booking->safariPackage->currency ?? 'KES' }} {{ number_format($booking->total_price / 100) }}
                                    </p>
                                </div>
                                <a href="{{ route('bookings.show', $booking) }}" class="inline-block bg-white text-primary border border-primary font-bold py-2 px-6 rounded-lg shadow-sm hover:bg-primary hover:text-white transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-suitcase-rolling text-gray-300 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No bookings found</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">You haven't booked any adventures yet. Your next great story is waiting to happen!</p>
                        <a href="{{ route('home') }}" class="inline-block bg-primary text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:bg-purple-700 transition-all transform hover:-translate-y-1">
                            Explore Packages
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- TAB: WISHLIST -->
            <div x-show="activeTab === 'wishlist'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                <!-- Fallback empty array if relation doesn't exist yet -->
                @forelse(Auth::user()->wishlist ?? [] as $package)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4 flex flex-col sm:flex-row sm:items-center gap-4 hover:shadow-md transition group">
                        <!-- Image -->
                        <div class="w-full sm:w-32 h-32 sm:h-24 rounded-lg overflow-hidden shrink-0 relative">
                             <img src="{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://via.placeholder.com/400' }}" alt="{{ $package->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow">
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $package->title }}</h4>
                            <p class="text-xs text-gray-500 mb-2"><i class="far fa-clock mr-1"></i> {{ $package->duration }} <span class="mx-1">•</span> {{ $package->location }}</p>
                            <p class="text-lg font-bold text-primary">{{ $package->currency }} {{ number_format($package->price / 100) }}</p>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('customer.packages.show', $package->slug ?? $package->id) }}" class="text-sm font-bold text-white bg-primary py-2 px-4 rounded hover:bg-purple-700 transition">View</a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-heart text-gray-300 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Your wishlist is empty</h3>
                        <p class="text-gray-500 mb-8">Save your favorite destinations here to view them later.</p>
                        <a href="{{ route('home') }}" class="inline-block bg-white text-primary border-2 border-primary font-bold py-2 px-6 rounded-lg hover:bg-primary hover:text-white transition-all">
                            Find Destinations
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection