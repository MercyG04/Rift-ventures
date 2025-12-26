@extends('layouts.custpageslayout')

@section('title', 'Traveler Stories')

@section('content')

    <!-- HERO HEADER -->
    <div class="bg-primary text-white py-16 text-center relative overflow-hidden">
        <!-- Abstract Pattern Overlay -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="relative z-10 px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">Traveler Stories</h1>
            <p class="text-blue-100 text-lg max-w-2xl mx-auto mb-8">
                Real experiences from real adventurers. See what our customers have to say about their journeys with SafariBook.
            </p>
            
            @auth
                <a href="{{ route('testimonials.create') }}" class="inline-flex items-center bg-secondary text-dark-text font-bold py-3 px-8 rounded-full shadow-lg hover:bg-white transition transform hover:scale-105">
                    <i class="fas fa-pen mr-2"></i> Write a Review
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center border-2 border-white text-white font-bold py-2 px-6 rounded-full hover:bg-white hover:text-primary transition">
                    Login to Write a Review
                </a>
            @endauth
        </div>
    </div>

    <!-- TESTIMONIALS GRID -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col h-full relative overflow-hidden group">
                        
                        <!-- Decorative Quote Icon -->
                        <i class="fas fa-quote-right absolute top-4 right-6 text-6xl text-gray-100 group-hover:text-primary/10 transition-colors"></i>

                        <!-- Content -->
                        <div class="mb-6 relative z-10 flex-grow">
                            <div class="flex text-secondary mb-4 text-sm">
                                @for($i=0; $i<$testimonial->rating; $i++) <i class="fas fa-star"></i> @endfor
                                @for($i=$testimonial->rating; $i<5; $i++) <i class="far fa-star text-gray-300"></i> @endfor
                            </div>
                            <p class="text-gray-600 italic leading-relaxed text-lg">
                                "{{ Str::limit($testimonial->content, 300) }}"
                            </p>
                            
                            <!-- Admin Reply (If exists) -->
                            @if($testimonial->admin_response)
                                <div class="mt-6 bg-blue-50 p-4 rounded-lg border-l-4 border-primary text-sm relative">
                                    <p class="text-primary font-bold text-xs uppercase mb-1 flex items-center">
                                        <i class="fas fa-reply mr-1"></i> SafariBook Response:
                                    </p>
                                    <p class="text-gray-700">{{ $testimonial->admin_response }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Author Footer -->
                        <div class="flex items-center mt-auto pt-6 border-t border-gray-100">
                            <!-- Avatar (Initials) -->
                            <div class="h-12 w-12 rounded-full bg-secondary text-primary font-bold flex items-center justify-center text-xl mr-4 shadow-sm border-2 border-white">
                                {{ substr($testimonial->author_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-dark-text text-base">{{ $testimonial->author_name }}</h4>
                                <p class="text-xs text-gray-400 font-medium">{{ $testimonial->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $testimonials->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-24 bg-white rounded-xl border-2 border-dashed border-gray-200">
                <div class="text-gray-300 text-7xl mb-6"><i class="far fa-comments"></i></div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No stories yet</h3>
                <p class="text-gray-500 mb-6">Be the first to share your experience!</p>
                @auth
                    <a href="{{ route('testimonials.create') }}" class="text-primary font-bold hover:underline text-lg">Write a Review &rarr;</a>
                @endauth
            </div>
        @endif
    </div>

@endsection
