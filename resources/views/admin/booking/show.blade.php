@extends('layouts.Adminmasterlayout')

@section('content')

<div class="max-w-5xl mx-auto pb-20">

    <!-- 1. HEADER & ACTIONS -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-extrabold text-gray-800">Booking #{{ $booking->id }}</h1>
                
                <!-- Status Badge -->
                @php
                    $color = $booking->status->color(); 
                    $badgeClass = "bg-{$color}-100 text-{$color}-800 border-{$color}-200";
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $badgeClass }}">
                    {{ $booking->status->label() }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1 ml-8">Created on {{ $booking->created_at->format('M d, Y @ H:i') }}</p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex gap-3">
            
            <!-- Resend Emails (Dropdown logic simulated for simplicity) -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="bg-white border border-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <i class="fas fa-envelope mr-2"></i> Resend Email
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 z-20 py-2" style="display: none;">
                    
                    <!-- Resend Confirmation / PII Link -->
                    @if($booking->status === \App\Enums\BookingStatus::CONFIRMED)
                        <form action="{{ route('admin.bookings.resend-confirmation', $booking) }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-gray-700">
                                Resend PII Link
                            </button>
                        </form>
                    @endif

                    <!-- Resend Cancellation -->
                    @if($booking->status === \App\Enums\BookingStatus::CANCELLED)
                        <form action="{{ route('admin.bookings.resend-cancellation', $booking) }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-50 text-sm text-red-600">
                                Resend Cancellation Info
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- UPDATE STATUS BUTTONS -->
            @if($booking->status === \App\Enums\BookingStatus::PENDING)
                <!-- Mark Confirmed (Manually Confirm Payment) -->
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" onsubmit="return confirm('Confirm payment received? This will send the PII link to the customer.');">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg shadow hover:bg-green-700 transition">
                        <i class="fas fa-check-circle mr-2"></i> Confirm Payment
                    </button>
                </form>
            @endif

            @if($booking->status !== \App\Enums\BookingStatus::CANCELLED && $booking->status !== \App\Enums\BookingStatus::COMPLETED)
                <!-- Cancel Booking -->
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="bg-red-100 text-red-600 font-bold py-2 px-4 rounded-lg hover:bg-red-200 transition border border-red-200">
                        Cancel Booking
                    </button>
                </form>
            @endif

        </div>
    </div>

    <!-- 2. INFO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Customer Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Customer Details</h3>
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($booking->user->name) }}&background=E5E7EB&color=374151">
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-900">{{ $booking->contact_name ?? $booking->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->contact_email ?? $booking->user->email }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->contact_phone ?? $booking->user->phone_number }}</p>
                    <p class="text-xs text-blue-600 mt-2">Registered User ID: {{ $booking->user_id }}</p>
                </div>
            </div>
        </div>

        <!-- Trip Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Trip Information</h3>
            <p class="text-lg font-bold text-primary mb-1">{{ $booking->safariPackage->title }}</p>
            <p class="text-sm text-gray-600 mb-3"><i class="fas fa-bed text-gray-400 mr-1"></i> {{ $booking->package_variant_name }}</p>
            
            <div class="flex justify-between text-sm border-t pt-3">
                <span class="text-gray-500">Dates:</span>
                <span class="font-medium">{{ $booking->booking_date->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-500">Travelers:</span>
                <span class="font-medium">{{ $booking->num_travelers }} People</span>
            </div>
        </div>

        <!-- Financial Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Payment Status</h3>
            <div class="flex items-center justify-between mb-2">
                <span class="text-3xl font-extrabold text-gray-900">{{ $booking->safariPackage->currency }} {{ number_format($booking->total_price / 100) }}</span>
            </div>
            
            <!-- Installment Logic Visualization (If you had payments table data) -->
            @php
                // Assuming you might add payment tracking back later, or relying on manual confirmation
                $isPaid = $booking->status === \App\Enums\BookingStatus::CONFIRMED;
            @endphp
            
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $isPaid ? '100%' : '0%' }}"></div>
            </div>
            
            <p class="text-sm {{ $isPaid ? 'text-green-600' : 'text-yellow-600' }} font-bold">
                {{ $isPaid ? 'Fully Paid' : 'Payment Pending' }}
            </p>
        </div>
    </div>

    <!-- 3. TRAVELER DETAILS (PII) SECTION -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Traveler Details (PII)</h2>
            
            @if($travelersComplete)
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">Complete</span>
            @else
                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-bold">Pending Input</span>
            @endif
        </div>

        @if($travelersComplete)
            <!-- SENSITIVE DATA TABLE -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-white text-gray-500 border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Passport / ID</th>
                            <th class="px-6 py-3 font-medium">DOB</th>
                            <th class="px-6 py-3 font-medium">Role</th>
                            
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($booking->travelerDetails as $traveler)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $traveler->full_name }}</td>
                            <td class="px-6 py-4 font-mono text-gray-600">
                                {{-- Decrypted automatically by Model --}}
                                {{ $traveler->passport_number ?? $traveler->id_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">{{ $traveler->date_of_birth }}</td>
                            <td class="px-6 py-4">
                                @if($traveler->is_primary_contact)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Primary</span>
                                @else
                                    <span class="text-xs text-gray-500">Guest</span>
                                @endif
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- EMPTY STATE / ACTION REQUIRED -->
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 mb-4">
                    <i class="fas fa-passport text-yellow-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Traveler details pending</h3>
                <p class="text-gray-500 text-sm mb-6">The customer has not yet filled out the secure PII form.</p>
                
                @if($booking->status === \App\Enums\BookingStatus::CONFIRMED)
                    <form action="{{ route('admin.bookings.resend-confirmation', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                            Resend PII Link Email
                        </button>
                    </form>
                @else
                    <p class="text-xs text-red-500 bg-red-50 inline-block px-3 py-1 rounded">
                        Booking must be confirmed before collecting details.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <!-- 4. SPECIAL REQUESTS -->
    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
        <h3 class="text-sm font-bold text-gray-500 uppercase mb-2">Special Requests & Notes</h3>
        <p class="text-gray-700 whitespace-pre-line">{{ $booking->special_requests ?? 'None provided.' }}</p>
    </div>

</div>

@endsection