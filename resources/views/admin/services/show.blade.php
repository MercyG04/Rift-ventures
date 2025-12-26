@extends('layouts.adminmasterlayout')

@section('content')

<div class="max-w-5xl mx-auto pb-20">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.services.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-800">Request #REQ-{{ $service->id }}</h1>
            
            <!-- Status Badge -->
            @php
                $color = $service->status->color(); 
                $badgeClass = "bg-{$color}-100 text-{$color}-800 border-{$color}-200";
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $badgeClass }}">
                {{ $service->status->label() }}
            </span>
        </div>

        <!-- ACTIONS -->
        <div class="flex gap-2">
            <!-- Status Update Form -->
            <form action="{{ route('admin.services.update', $service) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PUT')
                <select name="status" class="text-sm border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    @foreach(\App\Enums\ServiceStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $service->status === $status ? 'selected' : '' }}>
                            Mark as {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition">
                    Update
                </button>
            </form>

            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this request?');">
                @csrf @method('DELETE')
                <button class="bg-red-100 text-red-600 px-3 py-2 rounded-lg hover:bg-red-200 transition">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- 1. SECURE PII LINK GENERATOR -->
    <!-- Ideally shown when status is 'Booked' or 'Contacted' -->
    <div class="bg-blue-50 p-6 rounded-xl border border-blue-200 mb-8 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-user-shield text-blue-600 text-2xl mt-1"></i>
            </div>
            <div class="ml-4 w-full">
                <h3 class="font-bold text-blue-900 text-lg">Secure Traveler Data Collection</h3>
                <p class="text-sm text-blue-700 mb-3">
                    Copy this secure, signed link and email it to the client. It allows them to safely enter passport details for all <strong>{{ $service->adults + $service->children }} travelers</strong>.
                </p>
                
                <div class="flex items-center gap-2">
                    <input type="text" readonly 
                           value="{{ URL::temporarySignedRoute('services.travelers.edit', now()->addDays(7), ['service' => $service->id]) }}" 
                           class="w-full text-sm border-gray-300 rounded bg-white text-gray-600 font-mono select-all" 
                           id="piiLink">
                    <button onclick="copyToClipboard()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold text-xs uppercase tracking-wide">
                        Copy Link
                    </button>
                </div>
                <p class="text-xs text-blue-500 mt-2"><i class="fas fa-clock mr-1"></i> Link expires in 7 days.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <!-- 2. CLIENT DETAILS -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Contact Information</h3>
            <div class="space-y-3">
                <p class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Name:</span>
                    <span class="font-bold text-gray-900">{{ $service->contact_name }}</span>
                </p>
                <p class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-bold text-gray-900">{{ $service->contact_email }}</span>
                </p>
                <p class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-bold text-gray-900">{{ $service->contact_phone }}</span>
                </p>
                <p class="flex justify-between pt-1">
                    <span class="text-gray-600">User Account:</span>
                    @if($service->user_id)
                        <span class="text-green-600 font-bold text-xs bg-green-50 px-2 py-1 rounded">Registered</span>
                    @else
                        <span class="text-gray-400 text-xs">Guest</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- 3. TRIP DETAILS -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Request Details</h3>
            <div class="space-y-3">
                <p class="flex justify-between">
                    <span class="text-gray-600">Service:</span>
                    <span class="font-bold capitalize bg-gray-100 px-2 py-1 rounded">{{ str_replace('_', ' ', $service->service_type) }}</span>
                </p>
                <p class="flex justify-between">
                    <span class="text-gray-600">Destination:</span>
                    <span class="font-bold">{{ $service->destination }}</span>
                </p>
                <p class="flex justify-between">
                    <span class="text-gray-600">Travel Date:</span>
                    <span class="font-bold">{{ $service->travel_date ? $service->travel_date->format('M d, Y') : 'Not Set' }}</span>
                </p>
                <div class="mt-4 bg-gray-50 p-3 rounded text-sm text-gray-700">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Additional Requirements:</span>
                    <!-- Iterate JSON details -->
                    @if($service->additional_details)
                        @foreach($service->additional_details as $key => $value)
                            @if($value)
                                <div class="flex justify-between mb-1">
                                    <span class="capitalize text-gray-500">{{ str_replace('_', ' ', $key) }}:</span>
                                    <span class="font-medium">{{ $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <span class="italic text-gray-400">None provided.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TRAVELER PII TABLE (Decrypted View) -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Traveler Details (PII)</h2>
            <span class="text-xs text-gray-500">
                {{ $service->travelers->count() }} of {{ $service->adults + $service->children }} Submitted
            </span>
        </div>

        @if($service->travelers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-white text-gray-500 border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Full Name</th>
                            <th class="px-6 py-3 font-medium">Passport / ID</th>
                            <th class="px-6 py-3 font-medium">Expiry</th>
                            <th class="px-6 py-3 font-medium">DOB</th>
                            <th class="px-6 py-3 font-medium">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($service->travelers as $traveler)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $traveler->full_name }}</td>
                            <!-- DECRYPTED DATA DISPLAY -->
                            <td class="px-6 py-4 font-mono text-blue-600 bg-blue-50/50">
                                {{ $traveler->passport_number ?? $traveler->id_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">{{ $traveler->passport_expiry ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $traveler->date_of_birth }}</td>
                            <td class="px-6 py-4">
                                @if($traveler->is_primary_contact)
                                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded">Primary</span>
                                @else
                                    <span class="text-xs text-gray-500">Guest</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-gray-500">No traveler details submitted yet.</p>
                <p class="text-sm text-gray-400 mt-1">Use the link above to request details from the client.</p>
            </div>
        @endif
    </div>

</div>

<script>
    function copyToClipboard() {
        var copyText = document.getElementById("piiLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyText.value);
        alert("Secure Link Copied!");
    }
</script>

@endsection