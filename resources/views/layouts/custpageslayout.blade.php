<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rift Ventures Safaris | @yield('title', 'Discover Africa')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine JS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    
</head>
<body class="font-sans antialiased text-dark-text">

    <div class="fixed-bg"></div>

    <div class="page-bg-wipe flex flex-col min-h-screen" style="background-color: rgba(248, 249, 250, 0.95);" x-data="{ mobileMenuOpen: false }">

        <!-- STICKY NAVBAR -->
        <header class="sticky top-0 z-50 bg-white/95 shadow-md backdrop-blur-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    
                    <!-- Logo (Far Left) -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                            <img src="{{ asset('images/rift_ventures_logo.png') }}" alt="RiftVentures Logo" class="h-12 w-auto">
                            <span class="text-2xl font-extrabold tracking-tight text-gradient">
                                Rift Ventures Safaris
                            </span>
                        </a>
                    </div>

                    <!-- Desktop Nav (Middle) -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('home') }}" class="text-base font-semibold text-dark-text hover:text-primary transition duration-150 {{ request()->routeIs('home') ? 'text-primary' : '' }}">Home</a>
                        <a href="{{ route('local.index') }}" class="text-base font-semibold text-dark-text hover:text-primary transition duration-150 {{ request()->routeIs('local.*') ? 'text-primary' : '' }}">Local Tours</a>
                        <a href="{{ route('international.index') }}" class="text-base font-semibold text-dark-text hover:text-primary transition duration-150 {{ request()->routeIs('international.*') ? 'text-primary' : '' }}">International Tours</a>
                        <a href="{{ route('safaris.index') }}" class="text-base font-semibold text-dark-text hover:text-primary transition duration-150 {{ request()->routeIs('safaris.*') ? 'text-primary' : '' }}">Safaris</a>
                        <a href="{{ route('services.index') }}" class="text-base font-semibold text-dark-text hover:text-primary transition duration-150 {{ request()->routeIs('services.*') ? 'text-primary' : '' }}">Travel Services</a>
                    </nav>

                    <!-- Utility Nav (Far Right) -->
                    <div class="flex items-center space-x-4">
                        @guest
                            <div class="hidden sm:flex items-center space-x-3">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2 border border-primary text-sm font-medium rounded-full text-primary bg-transparent hover:bg-primary hover:text-white transition duration-150">
                                   Login
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-primary hover:bg-primary/90 shadow-md hover:shadow-lg transition duration-150">
                                    Sign Up
                                </a>
                            </div>
                        @endguest

                        @auth
                            <!-- Desktop Profile Link -->
                            <a href="{{ route('profile.dashboard') }}" class="hidden sm:flex items-center space-x-2 p-1 pr-3 rounded-full hover:bg-gray-100 transition duration-150 border border-transparent hover:border-gray-200">
                                <img class="h-9 w-9 rounded-full object-cover border border-gray-300" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=7a4d78&color=fff" 
                                     alt="{{ Auth::user()->name }}">
                                <span class="hidden lg:block text-sm font-semibold text-gray-700">
                                    {{ Str::limit(Auth::user()->name, 10) }}
                                </span>
                            </a>
                        @endauth

                        <!-- Mobile Menu Trigger -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md text-primary hover:bg-gray-100 focus:outline-none transition">
                            <span class="sr-only">Open menu</span>
                            <i x-bind:class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden absolute top-full left-0 w-full bg-white shadow-lg border-t border-gray-100 z-50"
                 style="display: none;">
                
                <div class="px-4 pt-4 pb-4 space-y-2">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-700 hover:text-primary hover:bg-gray-50 border-b border-gray-50">Home</a>
                    <a href="{{ route('local.index') }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-700 hover:text-primary hover:bg-gray-50 border-b border-gray-50">Local Tours</a>
                    <a href="{{ route('international.index') }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-700 hover:text-primary hover:bg-gray-50 border-b border-gray-50">International</a>
                    <a href="{{ route('safaris.index') }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-700 hover:text-primary hover:bg-gray-50 border-b border-gray-50">Safaris</a>
                    <a href="{{ route('services.index') }}" class="block px-3 py-2 rounded-md text-base font-bold text-gray-700 hover:text-primary hover:bg-gray-50">Travel Services</a>
                </div>
                
                <div class="pt-4 pb-6 border-t border-gray-200 bg-gray-50 px-5">
                    @guest
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 border border-primary rounded-full text-primary font-bold hover:bg-primary hover:text-white transition">Sign In</a>
                            <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 border border-transparent rounded-full text-white bg-primary font-bold hover:bg-primary/90">Sign Up</a>
                        </div>
                    @endguest

                    @auth
                        <div class="flex items-center mb-4">
                            <img class="h-10 w-10 rounded-full border border-gray-300" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=7a4d78&color=fff" alt="">
                            <div class="ml-3">
                                <p class="text-base font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('profile.dashboard') }}" class="block w-full text-center py-2.5 bg-primary text-white rounded-lg font-bold">
                                <i class="fas fa-th-large mr-2 text-sm"></i> My Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-center py-2.5 bg-white border border-red-200 text-red-600 rounded-lg font-bold">
                                    <i class="fas fa-sign-out-alt mr-2 text-sm"></i> Log Out
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="bg-primary text-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="col-span-1 md:col-span-1">
                        <span class="text-2xl font-bold">Rift Ventures Safaris</span>
                        <p class="text-white/80 text-sm leading-relaxed mt-4">
                            Experience Seamless Travel and Authentic Safari Adventures.
                        </p>
                        <div class="flex space-x-4 mt-6">
                            <a href="#" class="text-white/70 hover:text-secondary transition"><i class="fab fa-facebook-f fa-lg"></i></a>
                            <a href="#" class="text-white/70 hover:text-secondary transition"><i class="fab fa-instagram fa-lg"></i></a>
                        </div>
                    </div>
                    <!-- Destinations -->
                    <div>
                        <h3 class="text-lg font-bold text-secondary mb-4">Destinations</h3>
                        <ul class="space-y-2 text-sm text-white/80">
                            <li><a href="{{ route('local.index') }}" class="hover:text-white hover:underline transition">Local Tours</a></li>
                            <li><a href="{{ route('international.index') }}" class="hover:text-white hover:underline transition">International Tours</a></li>
                            <li><a href="{{ route('safaris.index') }}" class="hover:text-white hover:underline transition">Safaris</a></li>
                            <li><a href="{{ route('services.index') }}" class="hover:text-white hover:underline transition">Travel Services</a></li>
                        </ul>
                    </div>
                    <!-- Support -->
                    <div>
                        <h3 class="text-lg font-bold text-secondary mb-4">Support</h3>
                        <ul class="space-y-2 text-sm text-white/80">
                            <li><a href="{{ route('profile.dashboard') }}" class="hover:text-white hover:underline transition">My Account</a></li>
                            <li><a href="#" class="hover:text-white hover:underline transition">FAQs</a></li>
                            <li><a href="#" class="hover:text-white hover:underline transition">Terms & Conditions</a></li>
                        </ul>
                    </div>
                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-bold text-secondary mb-4">Contact</h3>
                        <ul class="space-y-3 text-sm text-white/80">
                            <li class="flex items-start"><i class="fas fa-map-marker-alt mt-1 mr-3 text-secondary"></i> <span>Kenyatta Street, Eldoret, Kenya</span></li>
                            <li class="flex items-center"><i class="fas fa-phone-alt mr-3 text-secondary"></i> <span>0768 296774</span></li>
                            <li class="flex items-center"><i class="fas fa-envelope mr-3 text-secondary"></i> <span>riftventuresafaris@gmail.com</span></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-white/10 mt-12 pt-8 text-center text-sm text-white/60">
                    &copy; {{ date('Y') }} Rift Ventures Safaris. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>