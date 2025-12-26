<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel |Rift Ventures Safaris</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine & Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside 
            class="flex-shrink-0 w-64 bg-gray-900 text-white transition-transform transform duration-300 ease-in-out"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
        >
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-16 bg-gray-800 shadow-md">
                
                 <img src="{{ asset('images/rift_ventures_logo.png') }}" alt="RiftVentures Logo" class="h-10 w-auto">
                <span class="text-lg font-bold tracking-wider uppercase text-secondary">Admin Panel</span>
                 </a>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 px-4 space-y-2">
                
                <!-- Packages Link (Acts as Dashboard) -->
                <a href="{{ route('admin.packages.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.packages.*') ? 'bg-primary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-suitcase-rolling w-6"></i>
                    <span class="font-medium">Packages</span>
                </a>

                <!-- Bookings Link -->
                <a href="{{ route('admin.bookings.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.bookings.*') ? 'bg-primary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-6"></i>
                    <span class="font-medium">Bookings</span>
                </a>
                
                <!-- Testimonials Link -->
                <a href="{{ route('admin.testimonials.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'bg-primary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-comments w-6"></i>
                    <span class="font-medium">Testimonials</span>
                </a>

                <!-- Custom Travel services -->
                <a href="{{ route('admin.services.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'bg-primary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-comments w-6"></i>
                    <span class="font-medium">Custom Requests</span>
                </a>

            </nav>
            
            <!-- Logout Section (Bottom) -->
            <div class="absolute bottom-0 w-full p-4 bg-gray-900 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-red-400 hover:text-red-300 transition-colors">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            
            <!-- Top Header (Toggle Sidebar) -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-4">Logged in as: <strong>{{ Auth::user()->name }}</strong></span>
                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-primary hover:underline">
                        Visit Site <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                        <p class="font-bold">Success</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Dynamic Page Content -->
                @yield('content')

            </main>
        </div>
    </div>

</body>
</html>