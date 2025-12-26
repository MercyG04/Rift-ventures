@extends('layouts.Adminmasterlayout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">All Safari Packages</h1>
    <a href="{{ route('admin.packages.create') }}" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">
        <i class="fas fa-plus mr-2"></i> Create New Package
    </a>
</div>

<!-- Search Filter (Optional but good) -->
<div class="bg-white p-4 rounded shadow-sm mb-6">
    <form action="{{ route('admin.packages.index') }}" method="GET" class="flex gap-4">
        <input type="text" name="search" placeholder="Search by title..." value="{{ request('search') }}" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
        <button type="submit" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Filter</button>
    </form>
</div>

<!-- Packages Table -->
<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                   Title
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Type / Category
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Price From
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Status
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($packages as $package)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12">
                            <img class="w-full h-full rounded object-cover" src="{{ $package->featured_image_path ? Storage::url($package->featured_image_path) : 'https://via.placeholder.com/50' }}" alt="" />
                        </div>
                        <div class="ml-3">
                            <p class="text-gray-900 font-bold whitespace-no-wrap">
                                {{ $package->title }}
                            </p>
                            <p class="text-gray-600 text-xs">{{ $package->location }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ $package->type->label() }}</p>
                    <span class="text-xs text-gray-500 uppercase">{{ $package->category->label() }}</span>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ $package->currency }} {{ number_format($package->starting_price / 100) }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $package->is_active ? 'text-green-900' : 'text-red-900' }}">
                        <span aria-hidden class="absolute inset-0 opacity-50 rounded-full {{ $package->is_active ? 'bg-green-200' : 'bg-red-200' }}"></span>
                        <span class="relative">{{ $package->is_active ? 'Active' : 'Inactive' }}</span>
                    </span>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                    <!-- Edit Button (Leads to Edit/Variants Page) -->
                    <a href="{{ route('admin.packages.edit', $package) }}" class="text-blue-600 hover:text-blue-900 mr-4 font-bold">Edit / Variants</a>
                    
                    
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    No packages found. Click "Create New" to create a new package.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
        {{ $packages->links() }}
    </div>
</div>

@endsection