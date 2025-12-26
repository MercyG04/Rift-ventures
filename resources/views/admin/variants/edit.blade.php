@extends('layouts.adminmasterlayout')

@section('content')

<div class="max-w-5xl mx-auto pb-20">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Edit Variant</h1>
            <p class="text-sm text-gray-500 mt-1">Editing option for: <strong class="text-primary">{{ $package->title }}</strong></p>
        </div>
        <div class="flex gap-3">
             <!-- Delete Button -->
            <form action="{{ route('admin.packages.variants.destroy', [$package, $variant]) }}" method="POST" onsubmit="return confirm('Delete this variant permanently?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-100 text-red-600 px-4 py-2 rounded-lg font-bold hover:bg-red-200 transition flex items-center h-full">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
            <a href="{{ route('admin.packages.edit', $package) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300 transition flex items-center">
                Cancel
            </a>
        </div>
    </div>

    <!-- PARENT CONTEXT CARD -->
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
                <p class="text-xs text-blue-600 mt-1 italic">Use the "Specific Inclusions" field below to add extras unique to this variant.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.packages.variants.update', [$package, $variant]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-md p-8 border-t-4 border-secondary">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- LEFT COLUMN: Variant Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Variant Name</label>
                        <input type="text" name="name" value="{{ old('name', $variant->name) }}" 
                               class="w-full bg-gray-50 border border-gray-300 rounded-lg h-12 px-4 focus:ring-secondary focus:border-secondary" required>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Price Per Person (Cents)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold text-sm bg-gray-100 rounded-l-lg border-r border-gray-300 px-3">
                                {{ $package->currency }}
                            </span>
                            <!-- Price Logic: Divide by 100 for display so admin sees 15000 not 1500000 -->
                            <input type="number" name="price" value="{{ old('price', $variant->price / 100) }}" 
                                   class="w-full bg-gray-50 border border-gray-300 rounded-r-lg h-12 pl-16 pr-4 focus:ring-secondary focus:border-secondary" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Enter amount in cents (e.g. 15000 = 150.00)</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Variant Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 focus:ring-secondary focus:border-secondary">{{ old('description', $variant->description) }}</textarea>
                    </div>

                    <!-- Specific Inclusions -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Specific Inclusions</label>
                        <textarea name="inclusions" rows="2" 
                                  class="w-full bg-gray-50 border border-gray-300 rounded-lg p-4 focus:ring-secondary focus:border-secondary">{{ old('inclusions', $variant->inclusions) }}</textarea>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Image Upload -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Variant Image</label>
                    
                    <div class="relative rounded-xl overflow-hidden shadow-sm border border-gray-200 aspect-video mb-4 bg-gray-100 group">
                        <!-- Show existing image or placeholder -->
                        <img id="variantPreview" 
                             src="{{ $variant->featured_image_path ? Storage::url($variant->featured_image_path) : 'https://via.placeholder.com/400?text=No+Image' }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer pointer-events-none">
                            <span class="text-white font-bold"><i class="fas fa-camera mr-2"></i> Change Image</span>
                        </div>

                        <input type="file" name="featured_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewVariantImage(this)">
                    </div>
                    <p class="text-xs text-gray-500 text-center">Click image to upload new file.</p>
                </div>

            </div>

            <!-- UPDATE BUTTON -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-secondary text-primary font-bold py-3 px-10 rounded-lg shadow hover:shadow-lg transition transform active:scale-95">
                    Update Variant
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    function previewVariantImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('variantPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection