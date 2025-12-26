@extends('layouts.Adminmasterlayout')

@section('content')

<div class="max-w-5xl mx-auto pb-20">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Add Variant</h1>
            <p class="text-sm text-gray-500 mt-1">Adding option for: <strong class="text-primary">{{ $package->title }}</strong></p>
        </div>
        <a href="{{ route('admin.packages.edit', $package) }}" class="text-gray-500 hover:text-gray-700 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Package
        </a>
    </div>

    <!-- PARENT CONTEXT CARD (Helpful Context) -->
    <div class="bg-blue-50 border-l-4 border-primary p-4 mb-8 rounded-r-lg shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-primary mt-1"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-blue-900">Parent Package Inclusions</h3>
                <p class="text-sm text-blue-700 mt-1">
                    This variant automatically inherits: 
                    @if($package->includes_flight) Flight, @endif
                    @if($package->includes_hotel) Accommodation Base, @endif
                    @if($package->includes_meals) Meals, @endif
                    @if($package->includes_transport) Transport @endif
                </p>
                <p class="text-xs text-blue-600 mt-1 italic">Use the "Specific Inclusions" field below to add extras unique to this variant (e.g., "Sea View", "Jacuzzi").</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.packages.variants.store', $package) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl shadow-md p-8 border-t-4 border-secondary">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- LEFT COLUMN: Variant Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Variant Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-secondary focus:border-secondary" 
                               placeholder="e.g. Deluxe Ocean View Suite" required>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Price Per Person</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold text-sm bg-gray-100 rounded-l-lg border-r border-gray-300 px-3">
                                {{ $package->currency }}
                            </span>
                            <!-- Removed the confusing "cents" logic from view. Admin enters "25000" normally -->
                            <input type="number" name="price" value="{{ old('price') }}" 
                                   class="w-full bg-gray-50 border border-gray-300 rounded-r-lg h-12 pl-16 pr-4 focus:ring-secondary focus:border-secondary" 
                                   placeholder="e.g. 25000" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Enter the amount for this variant.</p>
                    </div>
                    

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Variant Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 focus:ring-secondary focus:border-secondary" 
                                  placeholder="Briefly describe what makes this option special...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Specific Inclusions -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Specific Inclusions</label>
                        <textarea name="inclusions" rows="2" 
                                  class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 focus:ring-secondary focus:border-secondary" 
                                  placeholder="e.g. Private Balcony, Free Massage, Butler Service (Comma separated)">{{ old('inclusions') }}</textarea>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Image Upload -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Variant Image</label>
                    <p class="text-xs text-gray-500 mb-3">If left blank, the main package image will be used.</p>

                    <div class="relative rounded-xl overflow-hidden shadow-sm border-2 border-dashed border-gray-300 aspect-video mb-4 bg-gray-50 hover:bg-gray-100 transition flex items-center justify-center group">
                        <img id="variantPreview" src="#" class="w-full h-full object-cover hidden">
                        <div id="uploadPlaceholder" class="text-center p-6">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2 group-hover:text-secondary transition"></i>
                            <p class="text-sm text-gray-500 font-medium">Click to upload</p>
                        </div>
                        <input type="file" name="featured_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewVariantImage(this)">
                    </div>
                </div>

            </div>

            <!-- SUBMIT BUTTON -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-secondary text-primary font-bold py-3 px-10 rounded-lg shadow hover:shadow-lg transition transform active:scale-95">
                    Save Variant
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function previewVariantImage(input) {
        const preview = document.getElementById('variantPreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        
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
</script>

@endsection