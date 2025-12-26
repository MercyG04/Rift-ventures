@extends('layouts.adminmasterlayout')

@section('content')

<div class="max-w-6xl mx-auto pb-20">
    
    <!-- ERROR DEBUGGING BLOCK -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">There were problems with your input:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.packages.index') }}" class="text-gray-400 hover:text-primary transition"><i class="fas fa-arrow-left text-xl"></i></a>
            <h1 class="text-3xl font-extrabold text-dark-text">Edit: {{ $package->title }}</h1>
            
            <span class="ml-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $package->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $package->is_active ? 'Published' : 'Archived' }}
            </span>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('package.show', $package->slug) }}" target="_blank" class="bg-white border border-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition">
                <i class="fas fa-external-link-alt mr-2"></i> View Live
            </a>

            <!-- TOGGLE STATUS -->
            <form action="{{ route('admin.packages.toggle-status', $package) }}" method="POST">
                @csrf
                @method('PATCH')
                @if($package->is_active)
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-orange-600 transition shadow-sm flex items-center" onclick="return confirm('Archive this package?');">
                        <i class="fas fa-archive mr-2"></i> Archive
                    </button>
                @else
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-600 transition shadow-sm flex items-center">
                        <i class="fas fa-box-open mr-2"></i> Restore
                    </button>
                @endif
            </form>
        </div>
    </div>

    <!-- 1. PARENT PACKAGE FORM -->
    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-12">
            
            <!-- GRID LAYOUT: 2 Columns Left, 1 Column Right -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- LEFT COLUMN: Inputs -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Package Name</label>
                        <input type="text" name="title" value="{{ old('title', $package->title) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug Input (Hidden logic handled in controller, but shown here for edits) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Slug (URL)</label>
                        <input type="text" name="slug" value="{{ old('slug', $package->slug) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                        @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Location</label>
                            <input type="text" name="location" value="{{ old('location', $package->location) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                            @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Duration</label>
                            <input type="text" name="duration" value="{{ old('duration', $package->duration) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                            @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                                @foreach($types as $type)
                                    <option value="{{ $type->value }}" {{ old('type', $package->type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                                @foreach($categories as $category)
                                    <option value="{{ $category->value }}" {{ old('category', $package->category->value) == $category->value ? 'selected' : '' }}>{{ $category->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Starting Price (Cents)</label>
                            <input type="number" name="starting_price" value="{{ old('starting_price', $package->starting_price/100) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                            @error('starting_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Currency</label>
                            <select name="currency" class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->value }}" {{ old('currency', $package->currency->value) == $currency->value ? 'selected' : '' }}>{{ $currency->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 focus:ring-primary focus:border-primary">{{ old('description', $package->description) }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Itinerary</label>
                        <textarea name="itinerary" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 focus:ring-primary focus:border-primary">{{ old('itinerary', $package->itinerary) }}</textarea>
                    </div>

                    <!-- Inclusions Checkboxes -->
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Flags</h3>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            @php
                                $checkboxes = [
                                    'includes_flight' => 'Flight Included',
                                    'includes_hotel' => 'Hotel Included',
                                    'includes_sgr' => 'SGR Included',
                                    'includes_bus_transport' => 'Bus Transport',
                                    'is_featured' => 'is featured?',
                                    'is_special_offer' => 'Mark as Special Offer',
                                ];
                            @endphp
                            @foreach($checkboxes as $field => $label)
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $package->$field) ? 'checked' : '' }} class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary transition">
                                    <span class="text-sm font-medium text-gray-600">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        
                        <!-- Extra Inclusions Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-200 pt-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Other Inclusions</label>
                                <input type="text" name="other_inclusions" value="{{ old('other_inclusions', $package->other_inclusions) }}" class="w-full bg-white border border-gray-300 rounded-lg h-10 px-3 focus:ring-primary text-sm" placeholder="e.g. Park Fees, Water">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Exclusions</label>
                                <input type="text" name="exclusions" value="{{ old('exclusions', $package->exclusions) }}" class="w-full bg-white border border-gray-300 rounded-lg h-10 px-3 focus:ring-primary text-sm" placeholder="e.g. Tips, Visa">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Image -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hero Image</label>
                    <div class="relative rounded-xl overflow-hidden shadow-sm border border-gray-200 aspect-video mb-4 bg-gray-100">
                        <img id="imagePreview" 
                             src="{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://via.placeholder.com/400?text=No+Image' }}" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <label class="cursor-pointer w-full bg-white border border-gray-300 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition text-center block">
                        <span>Change Image</span>
                        <input type="file" name="featured_image" class="hidden" onchange="previewImage(this)">
                    </label>
                    
                    <!-- Image Validation Error -->
                    @error('featured_image')
                        <p class="text-red-500 text-xs mt-2 font-bold text-center">{{ $message }}</p>
                    @enderror
                    
                    <p class="text-xs text-gray-500 mt-2 text-center">Max 10MB. JPG/PNG.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-lg shadow hover:bg-blue-800 transition">
                    Save Changes
                </button>
            </div>
        </div>
    </form>

    <!-- 2. VARIANTS SECTION (Child Records) -->
    <div class="bg-white rounded-xl shadow-md p-8 border-t-4 border-secondary">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-extrabold text-dark-text">Variants / Rates</h2>
            <a href="{{ route('admin.packages.variants.create', $package) }}" 
               class="bg-secondary text-primary font-bold py-2 px-6 rounded-full shadow hover:shadow-lg transition transform hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i> Add Variant
            </a>
        </div>

        @if($package->variants->count() > 0)
            <div class="grid grid-cols-1 gap-4">
                @foreach($package->variants as $variant)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row items-center gap-6 group hover:shadow-md transition">
                        <div class="w-full md:w-32 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ $variant->featured_image_path ? Storage::url($variant->featured_image_path) : 'https://via.placeholder.com/150' }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="flex-grow text-center md:text-left">
                            <h3 class="text-lg font-bold text-dark-text">{{ $variant->name }}</h3>
                            <p class="text-sm text-gray-500">{{ Str::limit($variant->description, 60) }}</p>
                        </div>
                        <div class="text-center md:text-right min-w-[120px]">
                            <span class="block text-xs font-bold text-gray-400 uppercase">Rate</span>
                            <span class="text-xl font-extrabold text-primary">
                                {{ $package->currency }} {{ number_format($variant->price / 100) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.packages.variants.edit', [$package, $variant]) }}" class="text-gray-400 hover:text-primary transition p-2">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                        
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-10 text-center">
                <p class="text-gray-500 mb-4">No variants added yet.</p>
                <p class="text-sm text-gray-400">Click "Add Variant" to create bookable options.</p>
            </div>
        @endif
    </div>

</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection