@extends('layouts.adminmasterlayout')

@section('content')

<div class="max-w-6xl mx-auto pb-20">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Service Inquiries</h1>
            <p class="text-sm text-gray-500 mt-1">Manage Flights, Visas, and Custom Trip requests.</p>
        </div>
        
        <!-- Filter (Simple Search) -->
        <form action="{{ route('admin.services.index') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Search client name..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-5 py-3">Ref ID</th>
                        <th class="px-5 py-3">Client</th>
                        <th class="px-5 py-3">Service Type</th>
                        <th class="px-5 py-3">Destination / Date</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($inquiries as $service)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <!-- ID -->
                        <td class="px-5 py-4 whitespace-no-wrap">
                            <span class="font-mono text-sm font-bold text-gray-700">#REQ-{{ $service->id }}</span>
                        </td>

                        <!-- Client -->
                        <td class="px-5 py-4">
                            <div class="text-sm">
                                <p class="text-gray-900 font-bold whitespace-no-wrap">{{ $service->contact_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $service->contact_phone }}</p>
                            </div>
                        </td>

                        <!-- Type -->
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $service->service_type) }}
                            </span>
                        </td>

                        <!-- Details -->
                        <td class="px-5 py-4 text-sm text-gray-600">
                            <p>{{ $service->destination ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ $service->travel_date ? $service->travel_date->format('M d, Y') : 'Date Pending' }}</p>
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-4">
                            @php
                                $color = $service->status->color(); 
                                $badgeClass = "bg-{$color}-100 text-{$color}-800 border-{$color}-200";
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $badgeClass }}">
                                {{ $service->status->label() }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.services.show', $service) }}" class="text-primary hover:text-blue-800 font-bold text-sm">
                                Manage <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 border-b border-gray-200 bg-white text-sm text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="far fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>No inquiries found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
            {{ $inquiries->links() }}
        </div>
    </div>
</div>
@endsection