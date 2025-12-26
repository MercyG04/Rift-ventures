@extends('layouts.custpageslayout')

@section('title', 'Share Your Experience')

@section('content')

<div class="bg-gray-50 py-16 min-h-[80vh] flex items-center">
    <div class="max-w-2xl mx-auto px-4 w-full">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-dark-text mb-4">Share Your Story</h1>
            <p class="text-gray-600 text-lg">How was your adventure with SafariBook? We'd love to hear about it.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border-t-4 border-secondary relative overflow-hidden">
            <!-- Decorative Icon -->
            <i class="fas fa-pen-fancy absolute -top-6 -right-6 text-9xl text-gray-50 opacity-50 transform rotate-12 pointer-events-none"></i>

            <form action="{{ route('testimonials.store') }}" method="POST" class="relative z-10">
                @csrf

                <!-- 1. Star Rating Input -->
                <div class="mb-8 text-center">
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Rate your experience</label>
                    
                    <!-- Star Logic: Flex Row Reverse allows CSS hover logic to highlight previous stars -->
                    <div class="flex flex-row-reverse justify-center gap-2 group rating-stars">
                        @for($i=5; $i>=1; $i--)
                            <input type="radio" name="rating" id="star{{$i}}" value="{{$i}}" class="peer hidden" required>
                            <label for="star{{$i}}" 
                                   class="cursor-pointer text-gray-300 text-4xl transition-all duration-200 peer-checked:text-secondary hover:text-secondary peer-hover:text-secondary hover:scale-110">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Click a star to rate</p>
                    @error('rating') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- 2. Display Name -->
                <div class="mb-6">
                    <label for="author_name" class="block text-sm font-bold text-gray-700 mb-2">Display Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="author_name" id="author_name" 
                               value="{{ old('author_name', Auth::user()->name) }}" 
                               class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block pl-10 p-3 transition" 
                               placeholder="How should we call you?">
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-1">Leave blank to use your account name.</p>
                </div>

                <!-- 3. Review Content -->
                <div class="mb-8">
                    <label for="content" class="block text-sm font-bold text-gray-700 mb-2">Your Review</label>
                    <textarea name="content" id="content" rows="5" 
                              class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block p-4 transition" 
                              placeholder="Tell us about your favorite moments..." required>{{ old('content') }}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:bg-opacity-90 hover:shadow-xl transform active:scale-95 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Review
                </button>

            </form>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary transition font-medium flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Home
            </a>
        </div>

    </div>
</div>

<style>
    /* Star Rating Hover Effect Logic:
       1. Default state: All stars Gray.
       2. Checked state (peer-checked): The checked star turns Secondary (Yellow/Cyan).
       3. Hover state: The hovered star turns Secondary.
       4. Sibling Logic (~): Because of flex-row-reverse, the stars visually "before" the hovered/checked star are actually "after" it in the DOM.
          So, selecting a star highlights it and all its DOM siblings (which appear to the left).
    */
    .rating-stars:hover label { color: #e5e7eb; } /* Reset to gray on group hover to clear checked state visual if moving */
    .rating-stars label:hover,
    .rating-stars label:hover ~ label { color: #FFC72C !important; } /* Highlight hover + previous */
    
    input:checked ~ label { color: #FFC72C; } /* Highlight checked + previous */
</style>

@endsection
