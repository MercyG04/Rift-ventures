@extends('layouts.dashboard')

@section('title', 'My Wishlist')

@section('dashboard-content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-dark-text">My Wishlist</h1>
        <span class="bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full">{{ $wishlist->count() }} Saved</span>
    </div>

    @if($wishlist->count() > 0)
        <div class="space-y-6">
            @foreach($wishlist as $package)
                <!-- Reusing the Horizontal Card Component -->
                <!-- We wrap it in a div to add a 'Remove' button if desired, or handle removal inside the card logic later -->
                <div class="relative group">
                    <x-horizontal-package-card :package="$package" />
                    
                    <!-- Quick Remove Button (Absolute Positioned) -->
                    <button 
                        onclick="toggleWishlist({{ $package->id }})"
                        class="absolute top-4 right-4 bg-white p-2 rounded-full shadow text-red-500 hover:bg-red-50 transition z-20"
                        title="Remove from Wishlist"
                    >
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
            <div class="text-gray-300 text-6xl mb-4"><i class="far fa-heart"></i></div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Your wishlist is empty</h3>
            <p class="text-gray-500 mb-6">Save packages you're interested in to view them later.</p>
            <a href="{{ route('local.index') }}" class="btn-primary py-2 px-6">Explore Tours</a>
        </div>
    @endif

    <script>
        function toggleWishlist(packageId) {
            if(!confirm('Remove this trip from your wishlist?')) return;
            
            fetch(`/wishlist/toggle/${packageId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload(); // Simple reload to refresh the list
            });
        }
    </script>

@endsection
