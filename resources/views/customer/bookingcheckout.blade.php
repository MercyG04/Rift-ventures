@extends('layouts.custpageslayout')

@section('title', 'Review Booking - ' . $booking->safariPackage->title)

@section('content')
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

     @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-xl shadow-sm mb-8 animate-fade-in-down">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-green-800">Booking Registered Successfully!</h3>
                    <p class="text-green-700 mt-1">
                        Your booking request for <strong>{{ $booking->safariPackage->title }}</strong> has been received. 
                        An invoice has been sent to <strong>{{ $booking->contact_email }}</strong>. Please check your email for payment instructions.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- PAGE HEADER -->
    <div class="bg-gray-50 py-10 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-bold text-secondary tracking-wide uppercase mb-2">Review and Confirm booking.</p>
            <h1 class="text-4xl font-extrabold text-primary mb-2">Booking Summary</h1>
            <p class="text-xl text-gray-500 font-medium">Please review your trip details before confirming.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- LEFT COLUMN: TRIP DETAILS SUMMARY -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Trip Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-dark-text">Trip Details</h3>
                        <a href="{{ route('bookings.show', $booking->safariPackage) }}" class="text-sm text-secondary font-semibold hover:underline">
                            
                        </a>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Package -->
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Safari Package</p>
                            <p class="text-gray-800 font-bold">{{ $booking->safariPackage->title }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->safariPackage->duration }}</p>
                        </div>

                        <!-- Variant -->
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Accommodation / Variant</p>
                            <p class="text-gray-800 font-bold">
                                {{ $booking->package_variant_name ?? 'Standard Package' }}
                            </p>
                        </div>

                        <!-- Dates -->
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Dates</p>
                            <p class="text-gray-800 font-bold">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Check-in date
                            </p>
                        </div>

                        <!-- Travelers -->
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Travelers</p>
                            <p class="text-gray-800 font-bold">{{ $booking->num_travelers }} Person(s)</p>
                            <div class="text-xs text-gray-500 mt-1">
                                <!-- Parsing the breakdown from special_requests string if available, otherwise generic -->
                                @if(Str::contains($booking->special_requests, 'Adults:'))
                                    {{ Str::between($booking->special_requests, 'Adults:', ',') }} Adults,
                                    {{ Str::between($booking->special_requests, 'Children:', '.') }} Children
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-dark-text">Primary Contact</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Name</p>
                            <p class="text-gray-800 font-medium">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Email</p>
                            <p class="text-gray-800 font-medium">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Phone</p>
                            <p class="text-gray-800 font-medium">{{ Auth::user()->phone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Special Requests Display -->
                @if($booking->special_requests && !Str::startsWith($booking->special_requests, 'Contact Name:'))
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-dark-text">Special Requests</h3>
                        </div>
                        <div class="p-6">
                             <p class="text-gray-600 italic">
                                 "{{ Str::after($booking->special_requests, "\n") }}"
                             </p>
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT COLUMN: PRICE & ACTION -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Summary Card -->
                    <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Image -->
                        <div class="h-40 overflow-hidden relative">
                             <img 
                                src="{{ $booking->safariPackage->featured_image_path ? Storage::url($booking->safariPackage->featured_image_path) : 'https://via.placeholder.com/400' }}" 
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute inset-0 bg-black/10"></div>
                        </div>

                        <div class="p-6">
                            <h4 class="text-xl font-bold text-primary mb-4">Total Breakdown</h4>
                            
                            <div class="flex justify-between items-center mb-2 text-sm text-gray-600">
                                <span>Base Price (x{{ $booking->num_travelers }})</span>
                                <span class="font-medium">
                                    {{ $booking->safariPackage->currency }} 
                                    {{ number_format(($booking->total_price / $booking->num_travelers) / 100) }}
                                </span>
                            </div>

                            <div class="border-t border-gray-200 my-4 pt-4 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Total Amount</span>
                                <span class="text-2xl font-extrabold text-secondary">
                                    {{ $booking->safariPackage->currency }} {{ number_format($booking->total_price / 100) }}
                                </span>
                            </div>
                            
                            <form action="{{ route('bookings.confirm', $booking) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-primary text-white font-bold py-4 rounded-lg text-lg shadow-xl hover:bg-purple-700 hover:shadow-2xl transform active:scale-95 transition duration-300"
                                        :disabled="submitting"
                                        :class="{ 'opacity-50 cursor-not-allowed': submitting }"
                                >
                                    <!-- Normal State -->
                                    <span x-show="!submitting" class="flex items-center">
                                        Confirm & Checkout 
                                    </span>
                                    <!-- Loading State -->
                                    <span x-show="submitting" class="flex items-center" style="display: none;">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Processing Invoice...
                                    </span>
                                </button>
                            </form>

                            <p class="text-center text-xs text-gray-500 mt-4 leading-relaxed">
                                By clicking Confirm, you acknowledge that an invoice will be sent to your email with payment instructions.
                            </p>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="bg-blue-50 rounded-lg p-4 flex items-start space-x-3">
                        <i class="fas fa-lock text-blue-500 mt-1"></i>
                        <div>
                            <h5 class="text-sm font-bold text-blue-800">Secure Booking</h5>
                            <p class="text-xs text-blue-600 mt-1">Your details are safe. We do not charge your card instantly; payment is handled via invoice.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection