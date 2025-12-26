@extends('layouts.Adminmasterlayout')

@section('content')

<div class="max-w-5xl mx-auto pb-20">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Create New Package</h1>
        <a href="{{ route('admin.packages.index') }}" class="text-gray-500 hover:text-gray-700 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-md p-8">
            <!-- TOP SECTION: TWO COLUMNS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- LEFT COLUMN: Inputs -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Title & Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Package Name</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4" 
                                   placeholder="e.g. Magical Maasai Mara" required onkeyup="generateSlug()">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Slug (URL)</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4" 
                                   placeholder="e.g. magical-maasai-mara" required>
                            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Location -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" 
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4" 
                                   placeholder="e.g. Narok, Kenya" required>
                        </div>
                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Duration</label>
                            <input type="text" name="duration" value="{{ old('duration') }}" 
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4" 
                                   placeholder="e.g. 3 Days / 2 Nights" required>
                        </div>
                    </div>

                    <!-- Type & Category -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4">
                                @foreach($types as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4">
                                @foreach($categories as $category)
                                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Traveler Limits -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Min Travelers</label>
                            <input type="number" name="min_travelers" value="{{ old('min_travelers', 1) }}" min="1"
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Max Travelers (Optional)</label>
                            <input type="number" name="max_travelers" value="{{ old('max_travelers') }}" 
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4" 
                                   placeholder="Leave blank for unlimited">
                        </div>
                    </div>

                    <!-- Price & Currency -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Starting Price (Cents)</label>
                            <input type="number" name="starting_price" value="{{ old('starting_price') }}" 
                                   class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4" 
                                   placeholder="e.g. 1500000" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Currency</label>
                            <select name="currency" class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg h-12 px-4">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->value }}">{{ $currency->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg p-4" 
                                  placeholder="Short overview of the trip...">{{ old('description') }}</textarea>
                    </div>
                    
                    <!-- Itinerary -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Itinerary (Optional)</label>
                        <textarea name="itinerary" rows="4" 
                                  class="w-full bg-gray-100 border-transparent focus:border-primary focus:bg-white focus:ring-0 rounded-lg p-4" 
                                  placeholder="Day 1: Arrival..."></textarea>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Image Upload -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Package Image</label>
                    
                    <!-- Image Preview Container -->
                    <div class="bg-gray-200 rounded-xl h-64 w-full flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300 relative">
                        <img id="imagePreview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                        <span id="placeholderText" class="text-gray-500">No image selected</span>
                    </div>

                    <!-- Custom File Button -->
                    <div class="mt-4 text-center">
                        <label for="featured_image" 
                               class="cursor-pointer bg-secondary text-primary font-bold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition transform active:scale-95 inline-block">
                            Upload Image
                        </label>
                        <input type="file" id="featured_image" name="featured_image" class="hidden" onchange="previewImage(this)">
                        <p class="text-xs text-gray-500 mt-2">Max 10MB. JPG/PNG.</p>

                        @error('featured_image')
                            <p class="text-red-600 text-sm mt-2 font-bold animate-pulse">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- CHECKBOXES SECTION -->
            <div class="mt-10 pt-8 border-t border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Inclusions & Settings</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @php
                        $checkboxes = [
                            'is_featured' => 'Show on Home Slider',
                            'is_special_offer' => 'Special Offer?',
                            'includes_flight' => 'Includes Flight',
                            'includes_sgr' => 'Includes SGR',
                            'includes_bus_transport' => 'Bus Transport',
                            'includes_hotel' => 'Includes Hotel',
                            'includes_tour_guide' => 'Tour Guide',
                            'includes_excursions' => 'Excursions',
                            'includes_drinks' => 'Drinks',
                        ];
                    @endphp

                    @foreach($checkboxes as $name => $label)
                        <div class="flex items-center space-x-3 cursor-pointer">
                            <!-- Hidden input ensures a '0' is sent if unchecked, handled by controller boolean() but good practice -->
                            <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1" 
                                   class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer">
                            <label for="{{ $name }}" class="text-gray-700 font-medium cursor-pointer">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Other Inclusions (Comma separated)</label>
                        <input type="text" name="other_inclusions" class="w-full bg-gray-100 border-transparent rounded-lg h-10 px-3">
                    </div>
                    <div>
                         <label class="block text-sm font-bold text-gray-700 mb-1">Exclusions (Comma separated)</label>
                        <input type="text" name="exclusions" class="w-full bg-gray-100 border-transparent rounded-lg h-10 px-3">
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="mt-10 flex justify-end">
                <button type="submit" class="bg-primary text-white font-bold py-3 px-10 rounded-lg shadow hover:bg-blue-800 transition">
                    Create Package
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('placeholderText');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Auto-generate Slug from Title
    function generateSlug() {
        const title = document.getElementById('title').value;
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        document.getElementById('slug').value = slug;
    }
</script>

@endsection