@extends('layouts.dashboard')

@section('title', 'My Bookings')

@section('dashboard-content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-dark-text">My Booking History</h1>
    </div>

    @if($bookings->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-5 py-3">Trip</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Trip Name -->
                            <td class="px-5 py-4">
                                <div class="text-sm font-bold text-dark-text">{{ $booking->safariPackage->title }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->package_variant_name }}</div>
                            </td>
                            
                            <!-- Date -->
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $booking->booking_date->format('M d, Y') }}
                            </td>
                            
                            <!-- Price -->
                            <td class="px-5 py-4 text-sm font-bold text-primary">
                                {{ $booking->safariPackage->currency }} {{ number_format($booking->total_price / 100) }}
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                @php
                                    $color = $booking->status->color(); // Uses your Enum logic
                                    $class = "bg-{$color}-100 text-{$color}-800 border-{$color}-200";
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                    {{ $booking->status->label() }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('bookings.show', $booking) }}" class="text-secondary hover:text-primary font-bold text-sm">
                                    Manage <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
            <div class="text-gray-300 text-6xl mb-4"><i class="fas fa-suitcase-rolling"></i></div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">No bookings yet</h3>
            <p class="text-gray-500 mb-6">Ready for your next adventure?</p>
            <a href="{{ route('home') }}" class="btn-primary py-2 px-6">Start Exploring</a>
        </div>
    @endif

@endsection