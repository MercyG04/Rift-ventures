@extends('layouts.Adminmasterlayout')

@section('content')

<div class="max-w-6xl mx-auto pb-20">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800">Bookings Management</h1>
        
        <!-- Filter Dropdown (Simple implementation) -->
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex items-center space-x-3">
            <select name="status" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5" onchange="this.form.submit()">
                <option value="ALL">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>
            
            <!-- Search Box -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-64 pl-10 p-2.5" placeholder="Search ID or Name...">
            </div>
        </form>
    </div>

    <!-- BOOKINGS TABLE -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-5 py-3">Booking Ref</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Package / Variant</th>
                        <th class="px-5 py-3">Booking Date</th>
                        <th class="px-5 py-3">Total Price</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <!-- Booking Ref (ID) -->
                        <td class="px-5 py-4 whitespace-no-wrap">
                            <span class="font-mono text-sm font-bold text-gray-700">#{{ $booking->id }}</span>
                        </td>

                        <!-- Customer Name -->
                        <td class="px-5 py-4">
                            <div class="text-sm">
                                <p class="text-gray-900 font-bold whitespace-no-wrap">{{ $booking->contact_name ?? $booking->user->name }}</p>
                                <p class="text-gray-500 text-xs">{{ $booking->contact_email ?? $booking->user->email }}</p>
                            </div>
                        </td>

                        <!-- Package Info -->
                        <td class="px-5 py-4">
                            <p class="text-gray-900 text-sm font-medium">{{ $booking->safariPackage->title }}</p>
                            <p class="text-gray-500 text-xs mt-1">
                                <i class="fas fa-bed text-gray-400 mr-1"></i> {{ $booking->package_variant_name ?? 'Standard' }}
                            </p>
                        </td>

                        <!-- Date -->
                        <td class="px-5 py-4 text-sm text-gray-600">
                            {{ $booking->created_at->format('M d, Y') }}
                            <span class="block text-xs text-gray-400">{{ $booking->created_at->format('H:i A') }}</span>
                        </td>

                        <!-- Price -->
                        <td class="px-5 py-4 text-sm font-bold text-gray-800">
                            {{ $booking->safariPackage->currency }} {{ number_format($booking->total_price / 100) }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-5 py-4">
                            @php
                                $color = $booking->status->color(); 
                                
                                $badgeClass = "bg-{$color}-100 text-{$color}-800 border-{$color}-200";
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $color }}">
                                {{ $booking->status->label() }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center px-3 py-1.5 border border-primary text-primary text-xs font-bold rounded hover:bg-primary hover:text-white transition">
                                View Details <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 border-b border-gray-200 bg-white text-sm text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="far fa-calendar-times text-4xl mb-3 text-gray-300"></i>
                                <p>No bookings found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
            {{ $bookings->withQueryString()->links() }}
        </div>
    </div>

</div>

@endsection