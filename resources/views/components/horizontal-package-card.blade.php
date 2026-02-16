@props(['package'])

<!-- HORIZONTAL PACKAGE CARD -->
<div class="mb-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col md:flex-row h-auto md:h-56">
                    
    <!-- LEFT SIDE: IMAGE -->
    <div class="w-full md:w-1/3 h-48 md:h-full relative shrink-0">
        <img 
            src="{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://via.placeholder.com/600x400' }}" 
            alt="{{ $package->title }}" 
            loading="lazy"
            decoding="async"
            class="w-full h-full object-cover"
        >
    </div>

    <!-- RIGHT SIDE: CONTENT -->
    <div class="flex-grow p-5 flex flex-col justify-between">
        
        <!-- Top Section: Info -->
        <div>
            <!-- Title -->
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1 leading-tight">
                {{ $package->title }}
            </h2>
            
            <!-- Duration -->
            <p class="text-sm font-semibold text-gray-500 mb-3">
                <i class="far fa-clock mr-1"></i> {{ $package->duration }}
            </p>

            <!-- Inclusions Row (Location | Hotel | Transport) -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 font-medium">
                
                <!-- Location -->
                <span class="flex items-center">
                    <i class="fas fa-map-marker-alt text-primary mr-1.5"></i> {{ $package->location }}
                </span>

                <!-- Hotel Boolean -->
                @if($package->includes_hotel)
                    <span class="flex items-center">
                        <i class="fas fa-bed text-primary mr-1.5"></i> Hotel
                    </span>
                @endif

                <!-- Transport Booleans (Logic to show just one general label or specific ones) -->
                @if($package->includes_flight)
                    <span class="flex items-center"><i class="fas fa-plane text-primary mr-1.5"></i> Flight</span>
                @elseif($package->includes_sgr)
                    <span class="flex items-center"><i class="fas fa-train text-primary mr-1.5"></i> SGR</span>
                @elseif($package->includes_bus_transport)
                    <span class="flex items-center"><i class="fas fa-bus text-primary mr-1.5"></i> Bus</span>
                @endif

            </div>
        </div>

        <!-- Divider Line -->
        <div class="border-b border-gray-100 my-3"></div>

        <!-- Bottom Section: Price & Button -->
        <div class="flex justify-between items-end">
            
            <!-- Price Block -->
            <div>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wide">Starting from</p>
                <p class="text-2xl font-extrabold text-primary leading-none">
                    <span class="text-sm font-bold mr-0.5">{{ $package->currency }}</span>{{ number_format($package->starting_price / 100) }}
                </p>
            </div>

            <!-- View Package Button -->
            <!-- Using bg-secondary (Yellow) with text-primary (Purple/Dark) for high contrast visibility -->
            <a href="{{ route('package.show', $package->slug ?? $package->id) }}" 
               class="inline-block bg-secondary text-primary font-bold py-2.5 px-6 rounded-lg shadow-sm hover:bg-purple-400 transition-colors text-sm">
                View Package
            </a>

        </div>

    </div>
</div>
<!-- END HORIZONTAL CARD -->