@extends('layouts.custpageslayout')
@section('title', 'Home')

@section('content')

    <!-- 1. HERO SECTION (Carousel) -->
    <!-- Using Alpine.js for a simple slideshow if multiple hero packages exist -->
    <section class="relative h-[600px] md:h-[700px] w-full overflow-hidden" x-data="{ activeSlide: 0, slides: {{ $heroPackages->count() }} }">
        
        <!-- Slides -->
        @foreach($heroPackages as $index => $package)
            <div class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out"
                 x-show="activeSlide === {{ $index }}"
                 x-init="setInterval(() => { activeSlide = (activeSlide + 1) % slides }, 5000)"
            >
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-cover bg-center" 
                     style="background-image: url('{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://images.unsplash.com/photo-1516426122078-c23e76319801?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80' }}');">
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>

                <!-- Hero Content -->
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-4">
                    <span class="text-secondary font-bold tracking-wider uppercase mb-2 animate-fade-in-up">Discover Africa's Gems</span>
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight max-w-4xl animate-fade-in-up delay-100">
                        {{ $package->title }}
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl animate-fade-in-up delay-200">
                        {{ $package->description }}
                    </p>
                    <a href="{{ route('package.show', $package->slug) }}" class="btn-bold-action animate-fade-in-up delay-300">
                        View Details
                    </a>
                </div>
            </div>
        @endforeach

        <!-- Fallback if no featured packages -->
        @if($heroPackages->isEmpty())
            <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1600&q=80');">
                <div class="absolute inset-0 bg-black/40 flex justify-center items-center">
                    <h1 class="text-5xl font-bold text-white">Experience the Wild</h1>
                </div>
            </div>
        @endif
        
        <!-- Carousel Indicators -->
        @if($heroPackages->count() > 1)
            <div class="absolute bottom-24 left-0 right-0 flex justify-center space-x-3 z-20">
                @foreach($heroPackages as $index => $p)
                    <button @click="activeSlide = {{ $index }}" 
                            :class="{'bg-secondary w-8': activeSlide === {{ $index }}, 'bg-white/50 w-3': activeSlide !== {{ $index }}}"
                            class="h-3 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </section>

     
    <div class="relative -mt-16 z-30 max-w-6xl mx-auto px-4">
        @include('components.search-banner') <!-- Assuming you saved the search banner code here -->
    </div>

    <!-- 3. WHY CHOOSE US (Static Section - Image 2) -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text mb-4">Our<span class="text-primary"> Services</span></h2>
            <p class="text-gray-600 max-w-2xl mx-auto">We offer authentic travel experiences, ensuring your journey is safe, sustainable, and unforgettable.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-secondary text-center hover:-translate-y-2 transition-transform duration-300">
                <div class="w-16 h-16 mx-auto bg-primary/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-plane-departure text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-text mb-3">Air Ticketing</h3>
                <p class="text-sm text-gray-500">We handle flight booking, both for local and international flights.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-secondary text-center hover:-translate-y-2 transition-transform duration-300">
                <div class="w-16 h-16 mx-auto bg-primary/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-passport text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-text mb-3">Passport & Visas</h3>
                <p class="text-sm text-gray-500">We assist with passport application and visa processing to ensure seamless travel.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-secondary text-center hover:-translate-y-2 transition-transform duration-300">
                <div class="w-16 h-16 mx-auto bg-primary/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-hotel text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-text mb-3">Hotel Bookings</h3>
                <p class="text-sm text-gray-500"> Luxury resorts, budget stays, or business hotels. We secure the best accommodation for your comfort.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg border-t-4 border-secondary text-center hover:-translate-y-2 transition-transform duration-300">
                <div class="w-16 h-16 mx-auto bg-primary/10 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-map-marked-alt text-3xl text-primary"></i>
                </div>
                <h3 class="text-xl font-bold text-dark-text mb-3">Tour Packages</h3>
                <p class="text-sm text-gray-500">Curated adventures across Kenya and beyond. From beach getaways to thrilling safaris to beautiful tour destiantions.</p>
            </div>
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('services.index') }}" class="inline-block bg-primary text-white font-bold text-lg px-10 py-4 rounded-full shadow-xl hover:shadow-2xl hover:bg-opacity-90 transform hover:scale-105 transition duration-300">
                Travel Services
            </a>
            <p class="text-gray-500 text-sm mt-3">Need a specific Travel service? Let us know.</p>
        </div>

    </section>

    <!-- 4. SPECIAL OFFERS (Dynamic - Only shows if data exists) -->
    @if($specialOffers->count() > 0)
    <section class="py-12 bg-gradient-to-r from-primary/5 to-secondary/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div>
                    <h2 class="text-3xl font-extrabold text-dark-text">Special <span class="text-primary">Offers</span></h2>
                    <p class="text-gray-600 mt-2">Limited time deals you can't miss.</p>
                </div>
                <a href="{{ route('packages.offers') }}" class="hidden md:inline-block text-primary font-bold hover:text-primary transition">View All Offers &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($specialOffers as $package)
                    <x-packages-card :package="$package" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

     <!-- 5. POPULAR INTERNATIONAL DESTINATIONS -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text">Explore the <span class="text-primary">World</span></h2>
            <p class="text-gray-600 mt-3">Curated international experiences just for you.</p>
        <a href="{{ route('international.index') }}" class="hidden md:inline-block text-primary font-bold hover:text-primary transition">View All International Tours</a>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
            @foreach($internationalPackages as $package)
                <x-packages-card :package="$package" />
            @endforeach
        </div>

        
    </section>
   

    <!-- 5. POPULAR LOCAL DESTINATIONS -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text">Discover <span class="text-primary">Kenya's</span> Hidden Treasures</h2>
            <p class="text-gray-600 mt-3">Our most popular local destinations loved by travelers.</p>
            <a href="{{ route('local.index', ['sort' => 'popular']) }}" class="hidden md:inline-block text-primary font-bold hover:text-primary transition">View All Local Tours</a>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
            @foreach($popularDestinations as $package)
                <x-packages-card :package="$package" />
            @endforeach
        </div>
        
        
    </section>

    <!-- 6. WEEKEND GETAWAYS (Image 3 Style) -->
    <section class="py-20 bg-white/50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text">Great <span class="text-primary">Getaways</span></h2>
                <p class="text-gray-600 mt-3">Short trips perfect for the weekend.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
                @foreach($weekendGetaways as $package)
                    <x-packages-card :package="$package" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. TESTIMONIALS (Image 3 - Bottom) -->
    @if($testimonials->count() > 0)
    <section class="py-20 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" 
             x-data="{ 
                currentSlide: 0, 
                totalSlides: {{ $testimonials->count() }},
                visibleSlides: 3, 
                
                // Responsive logic: Update visible slides based on screen width
                updateVisibleSlides() {
                    if (window.innerWidth < 768) { this.visibleSlides = 1; }
                    else if (window.innerWidth < 1024) { this.visibleSlides = 2; }
                    else { this.visibleSlides = 3; }
                },

                next() {
                    // Logic to loop or stop at end. Here we stop at the end.
                    if (this.currentSlide < (this.totalSlides - this.visibleSlides)) {
                        this.currentSlide++;
                    }
                },
                
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    }
                }
             }"
             x-init="updateVisibleSlides(); window.addEventListener('resize', () => updateVisibleSlides())"
        >
            
            <!-- Header with Controls -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div class="text-center md:text-left mb-6 md:mb-0 w-full md:w-auto">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-dark-text">What Our <span class="text-primary">Customers Say</span></h2>
                    <a href="{{ route('testimonials.create') }}" class="text-primary hover:text-secondary font-medium mt-2 inline-block">Read all stories &rarr;</a>
                </div>

                <!-- Arrow Controls -->
                <!-- Only show if there are enough slides to scroll -->
                <div class="flex space-x-4" x-show="totalSlides > visibleSlides">
                    <button @click="prev()" 
                            :class="{'opacity-50 cursor-not-allowed': currentSlide === 0, 'hover:bg-primary hover:text-white': currentSlide > 0}"
                            class="w-12 h-12 rounded-full border-2 border-primary text-primary flex items-center justify-center transition duration-300">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button @click="next()" 
                            :class="{'opacity-50 cursor-not-allowed': currentSlide >= (totalSlides - visibleSlides), 'hover:bg-primary hover:text-white': currentSlide < (totalSlides - visibleSlides)}"
                            class="w-12 h-12 rounded-full border-2 border-primary text-primary flex items-center justify-center transition duration-300">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- The Carousel Track -->
            <div class="relative w-full">
                <div class="flex transition-transform duration-500 ease-in-out gap-8"
                     :style="'transform: translateX(-' + (currentSlide * (100 / visibleSlides)) + '%)'"
                >
                    @foreach($testimonials as $testimonial)
                        <!-- Individual Card Wrapper -->
                        <!-- logic: 'flex-shrink-0' prevents squishing. Width is calculated dynamically via style -->
                        <div class="flex-shrink-0 w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.33rem)]">
                            <div class="bg-white p-8 rounded-2xl shadow-lg border-b-4 border-primary hover:-translate-y-1 transition duration-300 relative h-full flex flex-col">
                                <!-- Quote Icon -->
                                <div class="absolute top-4 left-6 text-6xl text-primary/10 font-serif leading-none">“</div>
                                
                                <p class="text-gray-600 italic mb-6 relative z-10 pt-4 flex-grow">
                                    "{{ Str::limit($testimonial->content, 150) }}"
                                </p>
                                
                                <div class="flex items-center mt-auto pt-4 border-t border-gray-100">
                                    <div class="h-12 w-12 rounded-full bg-secondary/20 flex items-center justify-center text-secondary font-bold text-xl mr-4">
                                        {{ substr($testimonial->author_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-dark-text text-sm">{{ $testimonial->author_name }}</h4>
                                        <div class="text-secondary text-xs mt-1">
                                            @for($i=0; $i<$testimonial->rating; $i++) <i class="fas fa-star"></i> @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>
    @endif

    <!-- 8. NEWSLETTER (Image 4 - Purple BG) -->
    <section class="py-20 bg-gradient-to-r from-primary to-[#5d3a5b] text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Subscribe to our Newsletter!</h2>
            <p class="text-gray-200 mb-8 text-lg">Get the latest news about our offers, discounts and deals directly to your inbox.</p>
            
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                @csrf
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Your Email Address" 
                    required
                    class="flex-1 px-6 py-4 rounded-lg text-gray-800 focus:ring-4 focus:ring-secondary/50 border-none shadow-lg"
                >
                <button type="submit" class="bg-secondary text-dark-text font-bold py-4 px-8 rounded-lg hover:bg-[#05cde6] transition shadow-lg transform hover:scale-105">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

@endsection