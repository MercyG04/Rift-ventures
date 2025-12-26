@extends('layouts.custpageslayout')

@section('title', $variant->name . ' - ' . $package->title)

@section('content')

    <!-- 1. HERO SECTION & FLOATING TITLE -->
    <div class="relative h-[60vh] md:h-[75vh] w-full flex items-end">
        
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ $variant->featured_image_path ? Storage::url($variant->featured_image_path) : ($package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://via.placeholder.com/1600x900') }}" 
                alt="{{ $variant->name }}" 
                class="w-full h-full object-cover"
            >
            <!-- Gradient Overlay for text readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
        </div>

        <!-- Floating Title Content -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 md:pb-20">
            <a href="{{ route('package.show', $package->slug ?? $package->id) }}" class="inline-flex items-center text-white/80 hover:text-white mb-6 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to {{ $package->title }}
            </a>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 shadow-sm tracking-tight">{{ $variant->name }}</h1>
            <p class="text-xl md:text-2xl text-white/90 font-medium">
                {{ $package->title }} <span class="mx-2 opacity-60">|</span> {{ $package->duration }}
            </p>
        </div>
    </div>

    <!-- 2. MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- LEFT COLUMN (Details & Itinerary) -->
            <div class="lg:col-span-2 space-y-10">

                <!-- Variant Specific Overview -->
                <section class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-6">
                        <h2 class="text-3xl font-bold text-dark-text">Your Option Details</h2>
                        <div class="mt-4 md:mt-0 bg-secondary/10 text-secondary-dark px-4 py-2 rounded-lg">
                            <span class="block text-xs font-bold uppercase tracking-wider">Price Per Person</span>
                            <span class="text-2xl font-extrabold">{{ $package->currency }} {{ number_format($variant->price / 100, 0) }}</span>
                        </div>
                    </div>
                    
                    <div class="text-gray-700 leading-relaxed text-lg mb-8">
                        <p class="mb-4">{{ $variant->description ?? 'This option includes a standard room with full board access. Specific room type details are provided below.' }}</p>
                    </div>

                    <h3 class="text-xl font-bold text-dark-text mb-4">Specific Inclusions:</h3>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-600">
                        @if($variant->inclusions)
                            @foreach(explode(',', $variant->inclusions) as $inc)
                                <li class="flex items-start p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-check-circle text-secondary mt-1 mr-3"></i> 
                                    <span class="font-medium">{{ trim($inc) }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="flex items-start p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-secondary mt-1 mr-3"></i> 
                                <span class="font-medium">Standard {{ $package->location }} inclusions apply.</span>
                            </li>
                        @endif
                    </ul>
                </section>

                <!-- Itinerary Section (If provided on the parent package) -->
                @if($package->itinerary)
                <section>
                    <h2 class="text-3xl font-bold text-dark-text mb-6">Full Trip Itinerary</h2>
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-gray-700 leading-relaxed relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-secondary"></div>
                        <div class="prose max-w-none">
                            {!! nl2br(e($package->itinerary)) !!}
                        </div>
                    </div>
                </section>
                @endif
            </div>

            <!-- RIGHT COLUMN (Sticky Booking & Action) -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 bg-white p-6 rounded-xl shadow-lg border border-gray-100 ring-1 ring-black/5">
                    
                    <h3 class="text-2xl font-extrabold mb-2 text-dark-text">Ready to Book?</h3>
                    <p class="text-gray-600 text-sm mb-6">Confirm your accommodation choice below.</p>
                    
                    <!-- Selected Option Highlight -->
                    <div class="bg-blue-50 border-l-4 border-primary p-4 rounded-r mb-8">
                        <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-1">You are securing:</p>
                        <p class="text-xl font-extrabold text-primary leading-tight">
                            {{ $variant->name }}
                        </p>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                        <h3 class="font-bold text-sm text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Trip Summary</h3>
                        <div class="space-y-4 text-sm text-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500"><i class="far fa-clock mr-2 w-5"></i>Duration</span> 
                                <span class="font-bold text-dark-text">{{ $package->duration }} Days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500"><i class="fas fa-map-marker-alt mr-2 w-5"></i>Location</span> 
                                <span class="font-bold text-dark-text">{{ Str::limit($package->location, 18) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500"><i class="fas fa-user-friends mr-2 w-5"></i>Capacity</span> 
                                <span class="font-bold text-dark-text">
                                    {{ $package->min_travelers }} - {{ $package->max_travelers ?? 'Any' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Final Booking Link -->
                    <a href="{{ route('bookings.create', ['package' => $package->id, 'variant_id' => $variant->id]) }}" 
                       class="block w-full text-center py-4 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform transition-all duration-200 text-lg font-bold bg-primary text-white">
                        <i class="fas fa-check-circle mr-2"></i> Book This Option
                    </a>
                    
                    <p class="text-center text-xs text-gray-400 mt-4">
                        <i class="fas fa-shield-alt mr-1"></i> Secure Booking. No immediate charge.
                    </p>
                    
                </div>
            </div>

        </div>
    </div>
@endsection