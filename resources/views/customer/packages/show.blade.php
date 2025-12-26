@extends('layouts.custpageslayout')

{{-- Dynamic Title --}}
@section('title', $package->title)

@section('content')

    <!-- 1. HERO SECTION & FLOATING TITLE -->
    <div class="relative h-[60vh] md:h-[75vh] w-full">
        
        <!-- Background Image -->
        <!-- We use 'object-cover' to ensure it fills the space like your wireframe -->
        <div class="absolute inset-0">
            <img 
                src="{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://images.unsplash.com/photo-1519095612997-6fdb6062f568?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80' }}" 
                alt="{{ $package->title }}" 
                class="w-full h-full object-cover"
            >
            <!-- Gradient Overlay for better contrast -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/50"></div>
        </div>

        <!-- Wishlist Heart (Absolute Top Right) -->
        <!-- Uses Alpine to toggle the heart style immediately for UX feedback -->
        <div class="absolute top-8 right-8 z-20" x-data="{ inWishlist: {{ Auth::check() && Auth::user()->wishlist->contains($package->id) ? 'true' : 'false' }} }">
            <button 
                @click="fetch('{{ route('wishlist.toggle', $package) }}', { 
                    method: 'POST', 
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                }).then(res => res.json()).then(data => {
                    inWishlist = !inWishlist;
                    // Optional: You could show a toast notification here
                })"
                class="bg-white/30 backdrop-blur-md p-3 rounded-full hover:bg-white/50 transition shadow-lg group"
            >
                <!-- Dynamic Heart Icon -->
                <i class="fa-heart text-2xl transition-colors duration-300" 
                   :class="inWishlist ? 'fas text-yellow-600' : 'far text-white'"></i>
            </button>
        </div>

        <!-- Floating Title Banner (Overlapping the bottom edge) -->
        <div class="absolute bottom-0 left-0 w-full translate-y-1/2 z-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white rounded-t-2xl shadow-xl p-6 md:p-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <!-- Breadcrumb / Location -->
                        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2 font-semibold uppercase tracking-wide">
                            <span class="text-primary">{{ $package->category->label() }}</span>
                            <span>&bull;</span>
                            <span>{{ $package->location }}</span>
                        </div>
                        
                        <!-- Main Title -->
                        <h1 class="text-3xl md:text-5xl font-extrabold text-dark-text mb-2 font-serif">
                            {{ $package->title }}
                        </h1>
                    </div>

                    <!-- Duration Badge -->
                    <div class="mt-4 md:mt-0 flex items-center bg-gray-100 px-4 py-2 rounded-lg">
                        <i class="far fa-clock text-secondary mr-2 text-xl"></i>
                        <span class="font-bold text-gray-700">{{ $package->duration }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacer to push content down below the floating banner -->
    <div class="h-24 md:h-32 bg-light-bg"></div>

    <!-- 2. MAIN CONTENT (Overview & Details) -->
    <div class="bg-light-bg pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Overview -->
            <section class="mb-16">
                <h2 class="text-4xl font-extrabold text-dark-text mb-6">Overview</h2>
                <div class="prose max-w-4xl text-gray-600 leading-relaxed text-lg">
                    {!! nl2br(e($package->description)) !!}
                </div>
            </section>

            <!-- Trip Details Table -->
            <section class="mb-16">
                <h2 class="text-4xl font-extrabold text-dark-text mb-8">Trip Details</h2>
                
                <!-- The Grid Layout matching your Image 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 rounded-xl overflow-hidden shadow-lg border border-gray-200">
                    
                    <!-- Inclusions (Left) -->
                    <div class="bg-white border-b md:border-b-0 md:border-r border-gray-200">
                        
                        <div class="bg-secondary px-6 py-4">
                            <h3 class="font-bold text-white text-lg">Inclusions</h3>
                        </div>
                        <div class="p-6 md:p-8">
                            <ul class="space-y-4">
                                {{-- Loop through Booleans --}}
                                @if($package->includes_flight)
                                    <li class="flex items-start text-gray-600"><span class="text-gray-400 mr-3">•</span> Return Flights</li>
                                @endif
                                @if($package->includes_sgr)
                                    <li class="flex items-start text-gray-600"><span class="text-gray-400 mr-3">•</span> SGR Tickets</li>
                                @endif
                                @if($package->includes_hotel)
                                    <li class="flex items-start text-gray-600"><span class="text-gray-400 mr-3">•</span> Accommodation</li>
                                @endif
                                @if($package->includes_transport)
                                    <li class="flex items-start text-gray-600"><span class="text-gray-400 mr-3">•</span> All Transfers</li>
                                @endif
                                
                                {{-- Other Inclusions (Text) --}}
                                @if($package->other_inclusions)
                                    @foreach(explode(',', $package->other_inclusions) as $inc)
                                        <li class="flex items-start text-gray-600"><span class="text-gray-400 mr-3">•</span> {{ trim($inc) }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Exclusions (Right) -->
                    <div class="bg-white">
                        <!-- Orange Header -->
                        <div class="bg-secondary px-6 py-4">
                            <h3 class="font-bold text-white text-lg">Exclusions</h3>
                        </div>
                        <div class="p-6 md:p-8">
                            <ul class="space-y-4">
                                @if($package->exclusions)
                                    @foreach(explode(',', $package->exclusions) as $exc)
                                        <li class="flex items-start text-gray-600"><span class="text-gray-400 mr-3">•</span> {{ trim($exc) }}</li>
                                    @endforeach
                                @else
                                    <li class="text-gray-400 italic">Any item not mentioned as included.</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 3. AVAILABLE RATES / VARIANTS (With Alpine "Load More") -->
            <section id="variants" x-data="{ showAll: false, count: {{ $package->variants->count() }} }">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-4xl font-extrabold text-dark-text">Available Rates/ Variants</h2>
                    <!-- Optional counter -->
                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-bold" x-text="count + ' Options'"></span>
                </div>

                @if($package->variants->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        {{-- Loop through ALL variants --}}
                        @foreach($package->variants as $index => $variant)
                            
                            <div 
                                
                                x-show="showAll || {{ $index }} < 4"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                            >
                              <x-variant-card :package="$package" :variant="$variant" /> 
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- LOAD MORE BUTTON -->
                    <!-- Logic: Only show if NOT showing all AND total count is > 4 -->
                    <div class="mt-12 text-center" x-show="!showAll && count > 4">
                        <button 
                            @click="showAll = true" 
                            class="inline-flex items-center px-8 py-3 border border-primary text-primary font-bold rounded-full hover:bg-primary hover:text-white transition duration-300"
                        >
                            View More Options <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </div>

                @else
                    <div class="p-8 text-center bg-white rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500">No specific rates available online. Please contact us for a quote.</p>
                    </div>
                @endif

            </section>

        </div>
    </div>

@endsection