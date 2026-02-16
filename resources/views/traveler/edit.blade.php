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
            This connection is encrypted (AES-256), and your data is strictly used for travel reservations.
        </p>
    </div>

    <!-- Validation Error Summary -->
    @if ($errors->any())
        <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">There were issues with your submission</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @php
        // Determine Lock State: If data exists in DB, we lock the form
        // (Unless there are validation errors, which means we are re-showing the form to correct inputs)
        $hasData = $existingTravelers && $existingTravelers->count() > 0;
        $lastUpdate = $hasData ? $existingTravelers->first()->updated_at : null;
        $isLocked = false;

       if ($hasData && $lastUpdate) {
            $isLocked = $lastUpdate->diffInHours(now()) > 48;
        }
    @endphp

    @if($isLocked)
        <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-bold text-blue-800">Details Submitted Successfully</h3>
                    <p class="text-sm text-blue-700 mt-1">
                       The editing window for this form has closed (48 hours after submission). 
                        Your data is being processed.
                    </p>
                    <p class="text-xs text-blue-600 mt-2">
                        Need to make a correction? Please contact support immediately.
                    </p>
                </div>
            </div>
        </div>
        @elseif($hasData && !$errors->any())
        
        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-bold text-green-800">Details Saved Successfully</h3>
                    <p class="text-sm text-green-700 mt-1">
                        We have received your details. You can still update this form for the next 48 hours if you spot a mistake.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form with Alpine for Double-Click Prevention -->
    <form action="{{ $postRoute }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
        @csrf

        <div class="space-y-8">
            @for ($i = 0; $i < $totalTravelers; $i++)
                @php
                    $current = $existingTravelers[$i] ?? null;

                    // Defaults
                    $defaultName = '';
                    $defaultEmail = '';
                    
                    if ($i === 0 && !$current) {
                        $defaultName = $parentModel->contact_name ?? $parentModel->user->name ?? '';
                        $defaultEmail = $parentModel->contact_email ?? $parentModel->user->email ?? '';
                    } elseif ($current) {
                        $defaultName = $current->full_name;
                        $defaultEmail = $current->email;
                    }

                    $docState = ($current && $current->id_number && !$current->passport_number) ? 'id' : 'passport';
                    
                    if(old("travelers.{$i}.id_number")) $docState = 'id';
                    if(old("travelers.{$i}.passport_number")) $docState = 'passport';
                @endphp

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden" x-data="{ docType: '{{ $docState }}' }">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <span class="bg-gray-200 text-gray-600 w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">{{ $i + 1 }}</span>
                            Traveler #{{ $i + 1 }}
                        </h3>
                        @if($i == 0)
                            <span class="text-xs bg-primary/10 text-primary border border-primary/20 px-2 py-1 rounded font-semibold text-center">Primary Contact</span>
                        @endif
                    </div>
                    
                    @if($current)
                        <input type="hidden" name="travelers[{{ $i }}][id]" value="{{ $current->id }}">
                    @endif

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   name="travelers[{{ $i }}][full_name]" 
                                   value="{{ old("travelers.{$i}.full_name", $defaultName) }}"
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary disabled:bg-gray-100 disabled:text-gray-500"
                                   placeholder="As shown on ID/Passport"
                                   {{ $isLocked ? 'disabled' : '' }}>
                            @error("travelers.{$i}.full_name") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" 
                                   name="travelers[{{ $i }}][date_of_birth]" 
                                   value="{{ old("travelers.{$i}.date_of_birth", $current->date_of_birth ?? '') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary disabled:bg-gray-100 disabled:text-gray-500"
                                   {{ $isLocked ? 'disabled' : '' }}>
                            @error("travelers.{$i}.date_of_birth") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="email" 
                                   name="travelers[{{ $i }}][email]" 
                                   value="{{ old("travelers.{$i}.email", $defaultEmail) }}"
                                   class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary disabled:bg-gray-100 disabled:text-gray-500"
                                   placeholder="For flight updates"
                                   {{ $isLocked ? 'disabled' : '' }}>
                             @error("travelers.{$i}.email") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Document Selector -->
                        <div class="col-span-2 border-t border-gray-100 pt-4 mt-2">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Identification Document <span class="text-red-500">*</span></label>
                            @if(!$isLocked)
                                <div class="flex gap-6">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" x-model="docType" value="passport" class="text-primary focus:ring-primary h-5 w-5 border-gray-300">
                                        <span class="ml-2 text-gray-700 group-hover:text-primary transition">Passport</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="radio" x-model="docType" value="id" class="text-primary focus:ring-primary h-5 w-5 border-gray-300">
                                        <span class="ml-2 text-gray-700 group-hover:text-primary transition">National ID</span>
                                    </label>
                                </div>
                            @else
                                <!-- Static display when locked -->
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-sm font-bold uppercase" x-text="docType === 'passport' ? 'Passport' : 'National ID'"></span>
                            @endif
                        </div>

                        <!-- Passport Fields -->
                        <template x-if="docType === 'passport'">
                            <div class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-down">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Passport Number</label>
                                    <input type="text" 
                                           name="travelers[{{ $i }}][passport_number]" 
                                           value="{{ old("travelers.{$i}.passport_number", $current->passport_number ?? '') }}"
                                           class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary uppercase disabled:bg-gray-100 disabled:text-gray-500"
                                           placeholder="A1234567"
                                           {{ $isLocked ? 'disabled' : '' }}>
                                    @error("travelers.{$i}.passport_number") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Expiry Date</label>
                                    <input type="date" 
                                           name="travelers[{{ $i }}][passport_expiry]" 
                                           value="{{ old("travelers.{$i}.passport_expiry", $current->passport_expiry ?? '') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary disabled:bg-gray-100 disabled:text-gray-500"
                                           {{ $isLocked ? 'disabled' : '' }}>
                                    @error("travelers.{$i}.passport_expiry") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </template>

                        <!-- ID Field -->
                        <template x-if="docType === 'id'">
                            <div class="col-span-2 animate-fade-in-down">
                                <label class="block text-sm font-bold text-gray-700 mb-2">National ID Number</label>
                                <input type="text" 
                                       name="travelers[{{ $i }}][id_number]" 
                                       value="{{ old("travelers.{$i}.id_number", $current->id_number ?? '') }}"
                                       class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary disabled:bg-gray-100 disabled:text-gray-500"
                                       placeholder="12345678"
                                       {{ $isLocked ? 'disabled' : '' }}>
                                @error("travelers.{$i}.id_number") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </template>

                    </div>
                </div>
            @endfor
        </div>

        <!-- Submit Button OR Locked Message -->
        <div class="mt-10 text-center pb-12">
            @if(!$isLocked)
                <button type="submit" 
                        class="bg-green-600 text-white font-bold text-lg py-4 px-12 rounded-full shadow-xl hover:bg-green-700 hover:shadow-2xl transition transform active:scale-95 flex items-center justify-center mx-auto ring-4 ring-green-100"
                        :disabled="submitting"
                        :class="{ 'opacity-70 cursor-not-allowed': submitting }">
                    <span x-show="!submitting" class="flex items-center"><i class="fas fa-lock mr-2"></i> Submit Securely</span>
                    <span x-show="submitting" class="flex items-center"><i class="fas fa-circle-notch fa-spin mr-2"></i> Encrypting...</span>
                </button>
                <p class="text-xs text-gray-400 mt-4 max-w-xs mx-auto text-center">
                    <i class="fas fa-shield-alt mr-1"></i> 
                    Data is encrypted and stored in compliance with Data Protection Act.
                </p>
            @else
                <div class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-full text-gray-500 bg-gray-50">
                    <i class="fas fa-lock mr-2"></i> Form is currently locked
                </div>
            @endif
        </div>

    </form>
</div>

@endsection