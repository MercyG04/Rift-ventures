@extends('layouts.custpageslayout')

@section('content')


<div x-data="{
    currentTab: '{{ old('service_type', 'custom_trip') }}', 
    
    // Flight Logic
    flightType: '{{ old('flight_type', 'return') }}',
    flightRegion: '{{ old('flight_region', 'local') }}',
    
    // Arrays for Dynamic Dropdowns
    localAirlines: ['Kenya Airways', 'Jambojet', 'Fly540', 'Safarilink'],
    intlAirlines: ['Kenya Airways', 'Qatar Airways', 'Emirates', 'British Airways', 'Ethiopian Airlines', 'KLM', 'Air France', 'Turkish Airlines', 'RwandAir'],
    
    // Helper to get the right list
    get activeAirlines() {
        return this.flightRegion === 'local' ? this.localAirlines : this.intlAirlines;
    }
}" class="min-h-screen bg-gray-50 pb-20">

    <!-- HERO SECTION -->
    <div class="relative bg-primary text-white py-16">
        <div class="absolute inset-0 overflow-hidden">
             <!-- Abstract background pattern -->
             <svg class="absolute bottom-0 left-0 transform scale-150 opacity-10" viewBox="0 0 1440 320" fill="white"><path fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,213.3C1248,203,1344,213,1392,218.7L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Plan Your Trip & Access Travel Services</h1>
            <p class="text-white text-opacity-80 text-lg max-w-2xl mx-auto">
                Seamless travel starts here. Whether you need a custom safari, flight tickets, or document assistance, we've got you covered.
            </p>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container mx-auto px-4 -mt-10 relative z-20">
        
        <!-- FLASHER MESSAGES -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-lg flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <div>
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded shadow-lg flex items-center">
                <i class="fas fa-info-circle mr-3 text-xl"></i>
                <div>
                    <p class="font-bold">Welcome Back</p>
                    <p>{{ session('info') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-lg">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- SERVICE TABS NAVIGATION -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 mb-8">
            <!-- Tab A: Flight -->
            <button @click="currentTab = 'flight'" 
                :class="currentTab === 'flight' ? 'bg-primary ring-4 ring-primary/20 shadow-xl scale-105' : 'bg-primary opacity-80 hover:opacity-100'"
                class="text-white py-4 px-2 rounded-lg transition-all duration-300 flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-plane-departure text-2xl"></i>
                <span class="font-semibold text-sm md:text-base">Book Flights</span>
            </button>

            <!-- Tab B: Custom Trip (Default) -->
            <button @click="currentTab = 'custom_trip'" 
                :class="currentTab === 'custom_trip' ? 'bg-primary ring-4 ring-primary/20 shadow-xl scale-105' : 'bg-primary opacity-80 hover:opacity-100'"
                class="text-white py-4 px-2 rounded-lg transition-all duration-300 flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-map-marked-alt text-2xl"></i>
                <span class="font-semibold text-sm md:text-base">Plan Custom Trip</span>
            </button>

            <!-- Tab C: Visa -->
            <button @click="currentTab = 'visa'" 
                :class="currentTab === 'visa' ? 'bg-primary ring-4 ring-primary/20 shadow-xl scale-105' : 'bg-primary opacity-80 hover:opacity-100'"
                class="text-white py-4 px-2 rounded-lg transition-all duration-300 flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-passport text-2xl"></i>
                <span class="font-semibold text-sm md:text-base">Visa Assist</span>
            </button>

            <!-- Tab D: Passport -->
            <button @click="currentTab = 'passport'" 
                :class="currentTab === 'passport' ? 'bg-primary ring-4 ring-primary/20 shadow-xl scale-105' : 'bg-primary opacity-80 hover:opacity-100'"
                class="text-white py-4 px-2 rounded-lg transition-all duration-300 flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-id-card text-2xl"></i>
                <span class="font-semibold text-sm md:text-base">New Passport</span>
            </button>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white rounded-xl shadow-2xl p-6 md:p-10 border border-gray-100">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                
                <!-- HIDDEN INPUT: Sends the Service Type to the Backend -->
                <input type="hidden" name="service_type" x-model="currentTab">

                <!-- === SECTION 1: DYNAMIC SERVICE FIELDS === -->

                <!-- TAB A: FLIGHT BOOKING -->
                <div x-show="currentTab === 'flight'" x-transition.opacity.duration.300ms>
                    <div class="flex items-center space-x-2 mb-6 text-primary border-b pb-2">
                        <i class="fas fa-plane text-xl"></i>
                        <h3 class="text-xl font-bold">Flight Booking Details</h3>
                    </div>

                    <!-- Flight Type & Region Toggles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Region -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Flight Region</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 p-3 rounded border w-full hover:bg-gray-100">
                                    <input type="radio" name="flight_region" value="local" x-model="flightRegion" class="text-primary focus:ring-primary">
                                    <span>Local (Kenya)</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 p-3 rounded border w-full hover:bg-gray-100">
                                    <input type="radio" name="flight_region" value="international" x-model="flightRegion" class="text-primary focus:ring-primary">
                                    <span>International</span>
                                </label>
                            </div>
                        </div>

                        <!-- Trip Type -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Trip Type</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 p-3 rounded border w-full hover:bg-gray-100">
                                    <input type="radio" name="flight_type" value="one_way" x-model="flightType" class="text-primary focus:ring-primary">
                                    <span>One Way</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 p-3 rounded border w-full hover:bg-gray-100">
                                    <input type="radio" name="flight_type" value="return" x-model="flightType" class="text-primary focus:ring-primary">
                                    <span>Return</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Route & Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Flying From</label>
                            <input type="text" name="origin" placeholder="City or Airport" value="{{ old('origin') }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Flying To</label>
                            <input type="text" name="destination" placeholder="City or Airport" value="{{ old('destination') }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Departure Date</label>
                            <input type="date" name="travel_date" value="{{ old('travel_date') }}" min="{{ date('Y-m-d') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div x-show="flightType === 'return'" x-transition>
                            <label class="block text-gray-600 text-sm mb-1">Return Date</label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" min="{{ date('Y-m-d') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <!-- Airline & Class -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Preferred Airline</label>
                            <select name="airline" class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="">Select Airline...</option>
                                <template x-for="airline in activeAirlines" :key="airline">
                                    <option :value="airline" x-text="airline" :selected="airline == '{{ old('airline') }}'"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Class</label>
                            <select name="cabin_class" class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="economy" {{ old('cabin_class') == 'economy' ? 'selected' : '' }}>Economy</option>
                                <option value="business" {{ old('cabin_class') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="first" {{ old('cabin_class') == 'first' ? 'selected' : '' }}>First Class</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- END FLIGHT -->


                <!-- TAB B: CUSTOM TRIP -->
                <div x-show="currentTab === 'custom_trip'" x-transition.opacity.duration.300ms>
                    <div class="flex items-center space-x-2 mb-6 text-primary border-b pb-2">
                        <i class="fas fa-map-marked-alt text-xl"></i>
                        <h3 class="text-xl font-bold">Plan a Custom Holiday</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Destination -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-gray-700 font-medium mb-1">Destination / Interests</label>
                            <input type="text" name="destination" placeholder="e.g., Maasai Mara, Diani Beach, or 'Safari & Beach Combo'" 
                                value="{{ old('destination') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Dates & Duration -->
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Approx. Start Date</label>
                            <input type="date" name="travel_date" value="{{ old('travel_date') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Duration (Days)</label>
                            <input type="number" name="duration" placeholder="e.g., 5" value="{{ old('duration') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>

                        <!-- Budget -->
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Estimated Budget (Optional)</label>
                            <input type="text" name="budget_range" placeholder="e.g., $1000 - $2000" value="{{ old('budget_range') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <!-- Transport Mode -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-3">Preferred Mode of Transport</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-100 cursor-pointer transition">
                                <input type="radio" name="transport_mode" value="land_cruiser" {{ old('transport_mode') == 'land_cruiser' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                <span><i class="fas fa-shuttle-van mr-2"></i> 4x4 Land Cruiser</span>
                            </label>
                            <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-100 cursor-pointer transition">
                                <input type="radio" name="transport_mode" value="tour_van" {{ old('transport_mode') == 'tour_van' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                <span><i class="fas fa-bus mr-2"></i> Tour Van</span>
                            </label>
                            <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-100 cursor-pointer transition">
                                <input type="radio" name="transport_mode" value="flight" {{ old('transport_mode') == 'flight' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                                <span><i class="fas fa-plane mr-2"></i> Flying Package</span>
                            </label>
                        </div>
                    </div>

                    <!-- Add-ons -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <p class="font-medium text-gray-700 mb-2">Additional Services Needed:</p>
                        <div class="flex space-x-6">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="assistance_visa" value="1" {{ old('assistance_visa') ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                                <span>Visa Assistance</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="assistance_passport" value="1" {{ old('assistance_passport') ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                                <span>Passport Assistance</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- END CUSTOM TRIP -->


                <!-- TAB C: VISA ASSISTANCE -->
                <div x-show="currentTab === 'visa'" x-transition.opacity.duration.300ms>
                    <div class="flex items-center space-x-2 mb-4 text-primary border-b pb-2">
                        <i class="fas fa-passport text-xl"></i>
                        <h3 class="text-xl font-bold">Visa Application Assistance</h3>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <p class="text-yellow-700 text-sm">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Note:</strong> Visa application requirements, fees, and processing timelines vary significantly by country.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Destination Country</label>
                            <input type="text" name="visa_destination" placeholder="e.g., Dubai, USA, UK" value="{{ old('visa_destination') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-gray-600 font-medium mb-1">Purpose of Travel</label>
                            <select name="visa_purpose" class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                <option value="tourism">Tourism / Holiday</option>
                                <option value="business">Business / Conference</option>
                                <option value="study">Study / Education</option>
                                <option value="medical">Medical</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- END VISA -->


                <!-- TAB D: PASSPORT ASSISTANCE -->
                <div x-show="currentTab === 'passport'" x-transition.opacity.duration.300ms>
                    <div class="flex items-center space-x-2 mb-4 text-primary border-b pb-2">
                        <i class="fas fa-id-card text-xl"></i>
                        <h3 class="text-xl font-bold">Passport Services</h3>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <p class="text-blue-700 text-sm">
                            <i class="fas fa-clock mr-1"></i>
                            <strong>Timeline:</strong> Passport application and processing typically takes approximately <strong>1 month</strong>.
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-600 font-medium mb-3">Service Required</label>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="passport_service" value="new" checked class="text-primary focus:ring-primary">
                                <span>New Application (First Time)</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="passport_service" value="renewal" class="text-primary focus:ring-primary">
                                <span>Renewal (Expired or Filled)</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="passport_service" value="lost" class="text-primary focus:ring-primary">
                                <span>Replacement (Lost or Stolen)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- END PASSPORT -->


                <!-- === SECTION 2: COMMON CONTACT DETAILS === -->
                <div class="mt-10 pt-6 border-t border-gray-100">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">Contact Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Name -->
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Full Name</label>
                            <input type="text" name="contact_name" 
                                value="{{ old('contact_name', $user->name ?? '') }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary bg-gray-50" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Email Address</label>
                            <input type="email" name="contact_email" 
                                value="{{ old('contact_email', $user->email ?? '') }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary bg-gray-50" required>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-gray-600 text-sm mb-1">Phone Number</label>
                            <input type="tel" name="contact_phone" placeholder="+254..." 
                                value="{{ old('contact_phone', $user->phone ?? '') }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                        </div>

                        <!-- Number of Travelers -->
                        <div x-show="currentTab !== 'passport' && currentTab !== 'visa'">
                            <label class="block text-gray-600 text-sm mb-1">Total Travelers</label>
                            <input type="number" name="no_of_travellers" min="1" 
                                value="{{ old('no_of_travellers', 1) }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                    
                    <!-- Special Requests -->
                    <div class="mt-4">
                        <label class="block text-gray-600 text-sm mb-1">Additional Notes / Special Requests</label>
                        <textarea name="special_requests" rows="3" placeholder="Any dietary requirements, specific hotels, or extra details..."
                            class="w-full border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ old('special_requests') }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-4 px-10 rounded-full shadow-lg transform transition hover:scale-105">
                        Submit Request <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>

            </form>
        </div>
        
        <!-- TRUST INDICATORS -->
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-gray-500 opacity-70">
            <div><i class="fas fa-lock text-2xl mb-2"></i><p class="text-sm">Secure Handling</p></div>
            <div><i class="fas fa-headset text-2xl mb-2"></i><p class="text-sm">24/7 Support</p></div>
            <div><i class="fas fa-check-circle text-2xl mb-2"></i><p class="text-sm">Verified Agents</p></div>
            <div><i class="fas fa-plane-departure text-2xl mb-2"></i><p class="text-sm">Seamless Travel</p></div>
        </div>

    </div>
</div>
@endsection