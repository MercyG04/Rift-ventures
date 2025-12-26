<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rift Ventures Safaris') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-bg {
                /* The Three Rhinos Image */
                background-image: url('https://images.unsplash.com/photo-1518709594023-6eab9bab7b23?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
        </style>
    </head>
    <body class="font-sans text-dark-text antialiased">
        
        <div class="min-h-screen relative flex flex-col justify-center items-center auth-bg">
            
            <!-- Dark Overlay for Readability -->
            <div class="absolute inset-0 bg-black/40 z-0"></div>

            <!-- NAVBAR (Logo Section) -->
            <!-- White background strip to ensure the Purple logo pops, consistent with your brand -->
            <div class="absolute top-0 left-0 w-full bg-white/95 backdrop-blur-md shadow-md z-20 px-6 py-4 flex justify-between items-center">
                <a href="/" class="flex items-center space-x-2 group">
                    <!-- Logo Image -->
                    <img src="{{ asset('images/rift_ventures_logo.png') }}" alt="SafariBook" class="h-12 w-auto">
                    <!-- Brand Name in Primary Purple -->
                    <span class="text-2xl font-extrabold text-primary tracking-tight">
                        Rift Ventures Safaris
                    </span>
                </a>
                
                
            </div>

            <!-- AUTH CARD -->
            <!-- Centered, white with top border accent -->
            <div class="w-full sm:max-w-md mt-20 px-8 py-10 bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl z-10 border-t-4 border-secondary relative">
                
                <!-- Optional: Decorative Icon behind -->
                <i class="fas fa-paw absolute -bottom-4 -right-4 text-9xl text-gray-100 opacity-50 transform -rotate-12 pointer-events-none"></i>

                {{ $slot }}
            </div>

            <!-- Simple Footer -->
            <div class="mt-8 z-10 text-white/80 text-sm font-medium">
                &copy; {{ date('Y') }} RIft Ventures Safaris. All rights reserved.
            </div>

        </div>
    </body>
</html>