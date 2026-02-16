@props(['package', 'variant'])

<div class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 h-full flex flex-col">
    
    <!-- Image Section -->
    <div class="relative h-48 overflow-hidden">
        <img 
            src="{{ $variant->featured_image_path ? Storage::url($variant->featured_image_path) : ($package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80') }}" 
            alt="{{ $variant->name }}" 
            loading="lazy"
            decoding="async"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    <!-- Content Section -->
    <div class="p-5 flex flex-col flex-grow">
        
        <!-- Title -->
        <h3 class="text-lg font-bold text-dark-text mb-2 line-clamp-1" title="{{ $variant->name }}">
            {{ $variant->name }}
        </h3>

        <!-- Description (Optional - hidden if you removed it from main card, but good for context) -->
        <p class="text-sm text-gray-500 mb-4 flex-grow line-clamp-2">
            {{ $variant->description ?? 'Standard package options apply.' }}
        </p>

        <!-- Price & Action -->
        <div class="mt-auto pt-4 border-t border-gray-100">
            <div class="flex items-end justify-between">
                <div>
                    <span class="text-xs text-gray-400 block font-bold uppercase">Per Person</span>
                    <span class="text-xl font-bold text-primary">
                        {{ $package->currency }} {{ number_format($variant->price / 100) }}
                    </span>
                </div>
                
                <!-- BOOK BUTTON (Cyan Style) -->
                <a href="{{ route('customer.packages.variantdetails', [$package->id, $variant->id]) }}" 
                   class="btn-primary py-2 px-6 text-sm shadow-none hover:shadow-md">
                    Book
                </a>
            </div>
        </div>
    </div>
</div>