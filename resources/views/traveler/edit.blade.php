@extends('layouts.custpageslayout')

@section('title', 'Secure Traveler Details')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-12">
    
    <!-- Security Header -->
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
            <i class="fas fa-lock text-3xl"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-dark-text">Secure Traveler Data Entry</h1>
        <p class="text-gray-600 mt-2 max-w-xl mx-auto">
            Please provide the identification details for your upcoming trip. 
            This connection is encrypted, and your data will be stored securely.
        </p>
    </div>

    <!-- DYNAMIC FORM ACTION -->
    <!-- The controller must pass a variable $postRoute that contains the correct URL -->
    <form action="{{ $postRoute }}" method="POST">
        @csrf

        <!-- Dynamic Form Generation -->
        <div class="space-y-6">
            @php
                // Determine the total count based on which model is present
                // We check if $booking exists (Standard Booking) or $service exists (Custom Service)
                $totalTravelers = 1; 

                if (isset($booking)) {
                    $totalTravelers = $booking->num_travelers;
                } elseif (isset($service)) {
                    $totalTravelers = $service->no_of_travellers ?? 1;
                }
            @endphp

            @for ($i = 0; $i < $totalTravelers; $i++)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden" x-data="{ docType: 'passport' }">
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Traveler #{{ $i + 1 }}</h3>
                        @if($i == 0)
                            <span class="text-xs bg-primary text-white px-2 py-1 rounded">Primary Contact</span>
                        @endif
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Full Name -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name (As on ID/Passport)</label>
                            <input type="text" name="travelers[{{ $i }}][full_name]" required 
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                   placeholder="e.g. John Doe">
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="travelers[{{ $i }}][date_of_birth]" required 
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Document Selector -->
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Document Type</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" x-model="docType" value="passport" class="text-primary focus:ring-primary h-4 w-4">
                                    <span class="ml-2 text-gray-700">Passport</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" x-model="docType" value="id" class="text-primary focus:ring-primary h-4 w-4">
                                    <span class="ml-2 text-gray-700">National ID</span>
                                </label>
                            </div>
                        </div>

                        <!-- Passport Fields -->
                        <div class="col-span-2 md:col-span-1" x-show="docType === 'passport'">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Passport Number</label>
                            <input type="text" name="travelers[{{ $i }}][passport_number]" 
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary uppercase placeholder-gray-400"
                                   placeholder="A1234567">
                        </div>
                        <div class="col-span-2 md:col-span-1" x-show="docType === 'passport'">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" name="travelers[{{ $i }}][passport_expiry]" 
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>

                        <!-- ID Field -->
                        <div class="col-span-2" x-show="docType === 'id'" style="display: none;">
                            <label class="block text-sm font-bold text-gray-700 mb-2">National ID Number</label>
                            <input type="text" name="travelers[{{ $i }}][id_number]" 
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                   placeholder="12345678">
                        </div>

                    </div>
                </div>
            @endfor
        </div>

        <!-- Submit -->
        <div class="mt-8 text-center">
            <button type="submit" class="bg-green-600 text-white font-bold text-lg py-4 px-12 rounded-full shadow-xl hover:bg-green-700 transition transform hover:scale-105 flex items-center justify-center mx-auto">
                <i class="fas fa-shield-alt mr-2"></i> Submit Securely
            </button>
            <p class="text-xs text-gray-400 mt-4"><i class="fas fa-lock mr-1"></i> Data is encrypted using AES-256 standards.</p>
        </div>

    </form>
</div>

@endsection
    