<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rift Ventures Safaris | @yield('title', 'My Account')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine JS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Optional: Background pattern for the whole page */
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <!-- PURPLE BANNER HEADER -->
    <!-- Sticky top, primary color background, white text -->
    <header class="sticky top-0 z-50 bg-primary shadow-md transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand Name (Far Left) -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <!-- Logo Icon -->
                        <img src="{{ asset('images/rift_ventures_logo.png') }}" alt="RiftVentures Logo" class="h-12 w-auto bg-white rounded-full p-1 border-2 border-white/20">
                        <!-- Text is white to contrast with purple background -->
                        <span class="text-2xl font-extrabold tracking-tight text-white group-hover:text-white/90 transition">
                            Rift Ventures Safaris
                        </span>
                    </a>
                </div>

                <!-- Optional: Right side User info or Logout could go here in future -->
                <!-- For now, keeping it clean as requested -->
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <!-- flex-grow pushes the footer to the bottom -->
    <main class="flex-grow">
        
        <!-- Notification Area for Flash Messages (Success/Error) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
                    <div class="flex">
                        <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
                        <div class="ml-3"><p class="text-sm text-green-700 font-medium">{{ session('success') }}</p></div>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-500"><i class="fas fa-times"></i></button>
                </div>
            @endif
            
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
                    <div class="flex">
                        <div class="flex-shrink-0"><i class="fas fa-exclamation-circle text-red-400"></i></div>
                        <div class="ml-3"><p class="text-sm text-red-700 font-medium">{{ session('error') }}</p></div>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- SIMPLE MINIMAL FOOTER -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Rift Ventures Safaris. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>