@extends('layouts.custpageslayout')

@section('title', 'Book ' . $package->title)

@section('content')

    <!-- PAGE HEADER -->
    <div class="bg-gray-50 py-10 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold text-primary mb-2">Booking Form</h1>
            <p class="text-xl text-gray-500 font-medium">Complete your reservation for {{ $package->title }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- LEFT COLUMN: THE FORM -->
            <div class="lg:col-span-2">
                <form action="{{ route('bookings.store', $package) }}" method="POST" class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                    @csrf
                    
                    <!-- Hidden Variant ID -->
                    @if($selectedVariant)
                        <input type="hidden" name="variant_id" value="{{ $selectedVariant->id }}">
                    @endif

                    <!-- Section 1: Contact Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-dark-text border-b pb-2 mb-6">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Name -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                <input 
                                    type="text" 
                                    name="contact_name" 
                                    value="{{ old('contact_name', Auth::user()->name ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                                    placeholder="John Doe"
                                    required
                                >
                                @error('contact_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                <input 
                                    type="text" 
                                    name="contact_phone" 
                                    value="{{ old('contact_phone', Auth::user()->phone_number ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                                    placeholder="+254 700 000000"
                                    required
                                >
                                @error('contact_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                <input 
                                    type="email" 
                                    name="contact_email" 
                                    value="{{ old('contact_email', Auth::user()->email ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                                    placeholder="john@example.com"
                                    required
                                >
                                @error('contact_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Trip Details (With Alpine Date Logic) -->
                    <!-- FIX: Using (int)$package->duration ensures '5 Days / 4 Nights' becomes 5, not 54 -->
                    <div 
                        class="mb-8" 
                        x-data="{ 
                            startDate: '{{ old('booking_date') }}', 
                            duration: {{ (int)$package->duration > 0 ? (int)$package->duration : 1 }}, 
                            
                            get endDate() {
                                if (!this.startDate) return 'Select a date';
                                let date = new Date(this.startDate);
                                // Subtract 1 day for inclusive duration (Day 1 to Day 7 is 7 days total)
                                date.setDate(date.getDate() + Math.max(0, this.duration - 1));
                                return date.toDateString();
                            }
                        }"
                    >
                        <h3 class="text-lg font-bold text-dark-text border-b pb-2 mb-6">Trip Schedule</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Start Date -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Check-in Date</label>
                                <input 
                                    type="date" 
                                    name="booking_date" 
                                    x-model="startDate"
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                                    required
                                >
                                @error('booking_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Calculated End Date (Visual Only) -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Check-out Date (Calculated)</label>
                                <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-gray-600 font-medium h-[42px] flex items-center">
                                    <span x-text="endDate"></span>
                                </div>
                                <p class="text-xs text-primary mt-1">
                                    Based on <span x-text="duration"></span> days duration.
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Section 3: Travelers -->
                    <div class="mb-8" x-data="{ adults: {{ old('num_adults', 1) }}, children: {{ old('num_children', 0) }} }">
                        <h3 class="text-lg font-bold text-dark-text border-b pb-2 mb-6">Travelers</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Adults -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Number of Adults</label>
                                <input 
                                    type="number" 
                                    name="num_adults" 
                                    x-model="adults"
                                    min="1"
                                    max="{{ $package->max_travelers ?? 50 }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                                    required
                                >
                            </div>

                            <!-- Children -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Number of Children</label>
                                <input 
                                    type="number" 
                                    name="num_children" 
                                    x-model="children"
                                    min="0"
                                    class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                                >
                            </div>

                            <div class="col-span-2">
                                <div class="bg-blue-50 border border-blue-200 rounded p-3 text-sm text-blue-800">
                                    Total Travelers: <span class="font-bold" x-text="parseInt(adults) + parseInt(children)"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Special Requests -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-dark-text border-b pb-2 mb-6">Trip Preferences</h3>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Special Requests (Dietary, Accessibility, etc.)</label>
                        <textarea 
                            name="special_requests" 
                            rows="3" 
                            class="w-full rounded-lg border-gray-300 focus:border-secondary focus:ring-secondary"
                            placeholder="Tell us any specific requirements..."
                        >{{ old('special_requests') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-lg text-lg shadow-xl hover:bg-purple-700 hover:shadow-2xl transform active:scale-95 transition duration-300">
                        Complete Booking &rarr;
                    </button>
                    <p class="text-center text-xs text-gray-500 mt-4">You will receive an invoice and payment instructions in the next step.</p>

                </form>
            </div>

            <!-- RIGHT COLUMN: SUMMARY CARD -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden sticky top-24">
                    <!-- Image -->
                    <div class="h-48 overflow-hidden relative">
                        <img 
                            src="{{ $selectedVariant && $selectedVariant->featured_image_path ? Storage::url($selectedVariant->featured_image_path) : ($package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://via.placeholder.com/400') }}" 
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-black/20"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <h3 class="font-bold text-xl shadow-black drop-shadow-md">{{ $package->title }}</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Variant Info -->
                        @if($selectedVariant)
                            <div class="mb-6 pb-6 border-b border-gray-100">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Selected Option</p>
                                <h4 class="text-lg font-bold text-primary">{{ $selectedVariant->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($selectedVariant->description, 60) }}</p>
                            </div>
                            
                            <!-- Price Info -->
                            <div class="mb-2">
                                <p class="text-xs text-gray-400 uppercase font-bold">Price Per Person</p>
                                <p class="text-2xl font-extrabold text-dark-text">
                                    {{ $package->currency }} {{ number_format($selectedVariant->price / 100) }}
                                </p>
                            </div>
                        @else
                            <div class="bg-yellow-50 text-yellow-800 p-4 rounded text-sm mb-4">
                                <strong>Note:</strong> No specific room option selected. Base package rates apply.
                            </div>
                            <div class="mb-2">
                                <p class="text-xs text-gray-400 uppercase font-bold">Starting From</p>
                                <p class="text-2xl font-extrabold text-dark-text">
                                    {{ $package->currency }} {{ number_format($package->starting_price / 100) }}
                                </p>
                            </div>
                        @endif
                        
                        <div class="mt-6 text-xs text-gray-400 flex items-center justify-center">
                            <i class="fas fa-shield-alt mr-2"></i> Secure Booking
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection