@props(['package'])

<div 
    class="max-w-5xl mx-auto px-4 relative z-30"
    x-data="{
        location: '',
        duration: '',
        travelers: 1,
        results: [],
        isLoading: false,
        isOpen: false,

        // Real-time search logic
        async performSearch() {
            if (this.location.length < 2) {
                this.isOpen = false;
                return;
            }
            
            this.isLoading = true;
            this.isOpen = true;

            const params = new URLSearchParams({
                location: this.location,
                duration: this.duration,
                travelers: this.travelers
            });

            // Fetch from our API endpoint
            try {
                const response = await fetch(`/api/search/live?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();
                this.results = data.results || []; // Safety check if results is undefined
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            } finally {
                this.isLoading = false;
            }
        }
    }"
    @click.outside="isOpen = false"
>
    <!-- The Search Bar Container -->
    <!-- UPDATED: rounded-2xl on mobile (sharper corners), rounded-full on desktop (pill shape) -->
    <div class="bg-white rounded-2xl md:rounded-full shadow-2xl border border-gray-100 p-4 md:p-2">
        <form action="{{ route('packages.search') }}" method="GET" class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-200 gap-4 md:gap-0">
            
            <!-- 1. Destination Input -->
            <div class="relative flex-grow px-2 md:px-6 py-2 md:py-0">
                <label for="location" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Destination</label>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-secondary text-lg mr-3"></i>
                    <input 
                        type="text" 
                        name="location" 
                        id="location" 
                        x-model="location"
                        @input.debounce.300ms="performSearch()"
                        placeholder="Where are you going?" 
                        class="w-full bg-transparent border-none p-0 text-gray-900 placeholder-gray-400 focus:ring-0 font-semibold text-lg leading-tight"
                        autocomplete="off"
                    >
                </div>
                
                <!-- LIVE SEARCH DROPDOWN RESULTS -->
                <div x-show="isOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 w-full md:w-[350px] mt-4 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                     style="display: none;">
                    
                    <!-- Loading State -->
                    <div x-show="isLoading" class="p-4 text-center text-gray-400">
                        <i class="fas fa-circle-notch fa-spin"></i> Searching...
                    </div>

                    <!-- Results List -->
                    <ul x-show="!isLoading && results.length > 0" class="divide-y divide-gray-100">
                        <template x-for="result in results" :key="result.id">
                            <li>
                                <a :href="`/package/${result.slug}`" class="block p-3 hover:bg-gray-50 flex items-center transition">
                                    <img :src="result.featured_image_path ? `/storage/${result.featured_image_path}` : 'https://via.placeholder.com/60'" 
                                         class="w-12 h-12 rounded-lg object-cover mr-3 shadow-sm">
                                    <div>
                                        <div class="font-bold text-dark-text text-sm" x-text="result.title"></div>
                                        <div class="text-xs text-gray-500">
                                            <span x-text="result.location"></span> • <span x-text="result.currency"></span> <span x-text="result.price / 100"></span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </template>
                    </ul>

                    <!-- No Results -->
                    <div x-show="!isLoading && results.length === 0 && location.length > 2" class="p-4 text-center text-gray-500 text-sm">
                        No exact matches found.
                    </div>
                </div>
            </div>

            <!-- 2. Travelers Input -->
            <div class="px-2 md:px-6 py-2 md:py-0 w-full md:w-auto min-w-[160px]">
                <label for="travelers" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Guests</label>
                <div class="flex items-center">
                    <i class="fas fa-user-friends text-secondary text-lg mr-3"></i>
                    <input 
                        type="number" 
                        name="travelers" 
                        id="travelers" 
                        x-model="travelers"
                        min="1"
                        class="w-full bg-transparent border-none p-0 text-gray-900 placeholder-gray-400 focus:ring-0 font-semibold text-lg leading-tight"
                    >
                </div>
            </div>

            <!-- 3. Duration Input -->
            <div class="px-2 md:px-6 py-2 md:py-0 w-full md:w-auto min-w-[160px]">
                <label for="duration" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Days</label>
                <div class="flex items-center">
                    <i class="far fa-clock text-secondary text-lg mr-3"></i>
                    <input 
                        type="number" 
                        name="duration" 
                        id="duration" 
                        x-model="duration"
                        placeholder="Any"
                        min="1"
                        class="w-full bg-transparent border-none p-0 text-gray-900 placeholder-gray-400 focus:ring-0 font-semibold text-lg leading-tight"
                    >
                </div>
            </div>

            <!-- 4. Submit Button -->
            <div class="p-1 md:pl-2">
                <button type="submit" class="w-full md:w-auto h-12 px-8 rounded-xl md:rounded-full btn-bold-action flex items-center justify-center shadow-md hover:shadow-lg transform transition active:scale-95">
                    <i class="fas fa-search md:mr-2"></i> 
                    <span class="hidden md:inline">Search</span>
                    <span class="md:hidden">Search Trips</span>
                </button>
            </div>

        </form>
    </div>
</div>