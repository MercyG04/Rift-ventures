@extends('layouts.custpageslayout')

@section('title', $pageTitle)

@section('content')

    <!-- 1. HEADER BANNER -->
    <!-- Dynamic Title based on the route (Local, International, Safari) -->
    <div class="relative bg-primary text-white py-16 overflow-hidden">
        <!-- Abstract background pattern opacity -->
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">{{ $pageTitle }}</h1>
            @if(isset($pageSubtitle))
                <p class="text-blue-100 mt-3 text-lg md:text-xl max-w-2xl">{{ $pageSubtitle }}</p>
            @endif
        </div>
    </div>

    <!-- 2. TWO-COLUMN LAYOUT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            <!-- LEFT SIDEBAR (Dynamic Filters) -->
            <aside class="w-full lg:w-1/4 flex-shrink-0 space-y-8">
                
                <!-- Categories Widget -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden sticky top-24">
                    
                    {{-- 
                        DYNAMIC SIDEBAR LOGIC 
                        We check the route name to decide which list to show.
                    --}}

                    @if(request()->routeIs('local.*'))
                        {{-- 1. LOCAL TOURS SIDEBAR --}}
                        <div class="bg-primary text-white px-6 py-4 font-bold text-lg uppercase tracking-wide border-b border-primary/50">
                            Categories
                        </div>
                        
                        <!-- Section 1: Destinations -->
                        <div class="border-b border-gray-100">
                            <div class="bg-gray-50 px-6 py-3 font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center">
                                <i class="fas fa-umbrella-beach mr-2 text-secondary"></i> Destinations
                            </div>
                            <ul class="divide-y divide-gray-50">
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category' => 'destination']) }}" 
                                       class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200 {{ request('category') == 'destination' ? 'text-primary font-bold bg-blue-50 pl-8' : '' }}">
                                        All Destinations
                                    </a>
                                </li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Mombasa']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Mombasa Coast</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Diani']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Diani / Ukunda</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Watamu']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Watamu / Malindi</a></li>
                            </ul>
                        </div>

                        <!-- Section 2: Getaways -->
                        <div>
                            <div class="bg-gray-50 px-6 py-3 font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center">
                                <i class="fas fa-campground mr-2 text-secondary"></i> Getaways
                            </div>
                            <ul class="divide-y divide-gray-50">
                                 <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category' => 'getaway']) }}" 
                                       class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200 {{ request('category') == 'getaway' ? 'text-primary font-bold bg-blue-50 pl-8' : '' }}">
                                        All Getaways
                                    </a>
                                 </li>
                                 <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Naivasha']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Naivasha</a></li>
                                 <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Nanyuki']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Nanyuki</a></li>
                                 <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Nakuru']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Nakuru</a></li>
                            </ul>
                        </div>

                    @elseif(request()->routeIs('international.*'))
                        {{-- 2. INTERNATIONAL TOURS SIDEBAR --}}
                        <div class="bg-primary text-white px-6 py-4 font-bold text-lg uppercase tracking-wide border-b border-primary/50">
                            Tour Locations
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 px-6 py-3 font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center">
                                <i class="fas fa-globe-africa mr-2 text-secondary"></i> Popular Countries
                            </div>
                            <ul class="divide-y divide-gray-50">
                                <li>
                                    <a href="{{ route('international.index') }}" 
                                       class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200 {{ !request('search') ? 'text-primary font-bold bg-blue-50 pl-8' : '' }}">
                                        All International
                                    </a>
                                </li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Dubai']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Dubai</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Malaysia']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Malaysia</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Thailand']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Thailand</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Costa Rica']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Costa Rica</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Tanzania']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Tanzania</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Zambia']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Zambia</a></li>
                            </ul>
                        </div>

                    @elseif(request()->routeIs('safaris.*'))
                        {{-- 3. SAFARIS SIDEBAR --}}
                        <div class="bg-primary text-white px-6 py-4 font-bold text-lg uppercase tracking-wide border-b border-primary/50">
                            Safari Locations
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 px-6 py-3 font-bold text-xs text-gray-500 uppercase tracking-wider flex items-center">
                                <i class="fas fa-binoculars mr-2 text-secondary"></i> Popular Parks
                            </div>
                            <ul class="divide-y divide-gray-50">
                                <li>
                                    <a href="{{ route('safaris.index') }}" 
                                       class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200 {{ !request('search') ? 'text-primary font-bold bg-blue-50 pl-8' : '' }}">
                                        All Safaris
                                    </a>
                                </li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Maasai Mara']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Maasai Mara</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Tsavo']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Tsavo East/West</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Amboseli']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Amboseli</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Nakuru']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Lake Nakuru</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['search' => 'Serengeti']) }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary hover:pl-8 transition-all duration-200">Serengeti</a></li>
                            </ul>
                        </div>
                    
                    @else
                        {{-- FALLBACK FOR GENERIC PAGES --}}
                        <div class="bg-primary text-white px-6 py-4 font-bold text-lg uppercase tracking-wide border-b border-primary/50">
                            Categories
                        </div>
                        <div>
                             <ul class="divide-y divide-gray-50">
                                <li><a href="{{ route('home') }}" class="block px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-primary transition-all duration-200">Home</a></li>
                             </ul>
                        </div>
                    @endif

                </div>

            </aside>

            <!-- RIGHT MAIN CONTENT (Package List) -->
            <div class="w-full lg:w-3/4">
                
                <!-- Sorting Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
                    <p class="text-gray-600 text-sm mb-2 sm:mb-0">
                        Showing <span class="font-bold text-dark-text">{{ $packages->firstItem() ?? 0 }}-{{ $packages->lastItem() ?? 0 }}</span> of {{ $packages->total() }} packages
                    </p>
                    
                    <div class="flex items-center">
                        <label for="sort" class="text-sm text-gray-500 mr-3 font-medium">Sort by:</label>
                        <select id="sort" onchange="window.location.href=this.value" class="form-select border-gray-300 rounded-md text-sm py-1.5 pl-3 pr-8 focus:ring-primary focus:border-primary cursor-pointer">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest Added</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- The Loop: Dynamic Content -->
                @forelse($packages as $package)
                    <!-- Using the Horizontal Card Component -->
                    <x-horizontal-package-card :package="$package" />
                @empty
                    <!-- Empty State -->
                    <div class="text-center py-16 bg-white rounded-xl border-2 border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="far fa-compass"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700 mb-1">No packages found</h3>
                        <p class="text-gray-500 max-w-xs mx-auto">We couldn't find any trips matching your criteria. Try selecting a different category.</p>
                        <a href="{{ route('home') }}" class="mt-6 inline-block text-primary font-bold hover:underline">Browse all packages</a>
                    </div>
                @endforelse

                <!-- Pagination Links -->
                <div class="mt-10">
                    {{ $packages->withQueryString()->links() }}
                </div>

            </div>

        </div>
    </div>

@endsection