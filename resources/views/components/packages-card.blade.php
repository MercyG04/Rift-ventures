@props(['package'])

<div class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100 h-full flex flex-col">
    <!-- Image Section -->
    <div class="relative h-64 overflow-hidden">
        <!-- Badge for Type (Local/Intl) -->
        <span class="absolute top-4 left-4 z-10 bg-white/90 text-primary text-xs font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wider">
            {{ $package->type->label() }}
        </span>

        <!-- Special Offer Badge (Conditional) -->
        @if($package->is_special_offer)
            <span class="absolute top-4 right-4 z-10 bg-secondary text-dark-text text-xs font-bold px-3 py-1 rounded-full shadow-sm animate-pulse">
                Special Offer
            </span>
        @endif

        <img 
            src="{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
            alt="{{ $package->title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
        >
        
        <!-- Overlay Gradient for text readability if needed -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    <!-- Content Section -->
    <div class="p-6 flex flex-col flex-grow">
        <!-- Category & Location -->
        <div class="flex justify-between items-center mb-2 text-sm text-gray-500">
            <span class="flex items-center"><i class="fas fa-map-marker-alt mr-1 text-secondary"></i> {{ $package->location }}</span>
            <span class="flex items-center"><i class="far fa-clock mr-1 text-secondary"></i> {{ $package->duration }}</span>
        </div>

        <!-- Title -->
        <h3 class="text-xl font-bold text-dark-text mb-2 line-clamp-1">{{ $package->title }}</h3>

        

        <!-- Price & Action -->
        <div class="mt-auto pt-4 border-t border-gray-100">
            <div class="flex items-end justify-between">
                <div>
                    <span class="text-xs text-gray-400 block">Starting from</span>
                    <span class="text-xl font-bold text-primary">
                        {{ $package->currency }} {{ number_format($package->starting_price / 100) }}
                    </span>
                </div>
                
                <!-- View More Button (Cyan Background as requested) -->
                <a href="{{ route('package.show', $package->slug) }}" class="btn-primary py-2 px-4 text-sm shadow-none hover:shadow-md">
                    View Package
                </a>
            </div>
        </div>
    </div>
</div>
