@extends('layouts.adminmasterlayout')

@section('content')

<div class="max-w-6xl mx-auto pb-20">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Testimonials</h1>
            <p class="text-sm text-gray-500 mt-1">Manage customer reviews and replies.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="bg-primary hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Manual Review
        </a>
    </div>

    <!-- TESTIMONIALS LIST -->
    <div class="space-y-6">
        @forelse($testimonials as $testimonial)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ showReplyForm: false }">
                
                <!-- Card Header: User Info & Status -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg mr-3">
                            {{ substr($testimonial->author_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-bold text-sm">{{ $testimonial->author_name }}</h3>
                            <p class="text-gray-500 text-xs">
                                {{ $testimonial->created_at->format('M d, Y') }} 
                                @if($testimonial->user_id) 
                                    <span class="text-blue-600 bg-blue-50 px-1.5 rounded ml-1" title="Registered User">Verified</span> 
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $testimonial->is_approved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $testimonial->is_approved ? 'Published' : 'Pending Approval' }}
                    </span>
                </div>

                <!-- Card Body: Content -->
                <div class="p-6">
                    <!-- Rating Stars -->
                    <div class="flex items-center mb-3 text-yellow-400 text-sm">
                        @for($i=0; $i<$testimonial->rating; $i++) <i class="fas fa-star"></i> @endfor
                        @for($i=$testimonial->rating; $i<5; $i++) <i class="far fa-star text-gray-300"></i> @endfor
                    </div>

                    <!-- Review Text -->
                    <p class="text-gray-700 italic mb-4">"{{ $testimonial->content }}"</p>

                    <!-- Existing Admin Response -->
                    @if($testimonial->admin_response)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r mb-4">
                            <p class="text-xs font-bold text-blue-800 uppercase mb-1">Our Response:</p>
                            <p class="text-blue-900 text-sm">{{ $testimonial->admin_response }}</p>
                        </div>
                    @endif

                    <!-- Action Toolbar -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        
                        <!-- Approve Button (Only if pending) -->
                        @if(!$testimonial->is_approved)
                            <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-bold flex items-center transition">
                                    <i class="fas fa-check-circle mr-1"></i> Approve
                                </button>
                            </form>
                            <span class="text-gray-300">|</span>
                        @endif

                        <!-- Toggle Reply Form -->
                        <button @click="showReplyForm = !showReplyForm" class="text-primary hover:text-blue-800 text-sm font-bold flex items-center transition">
                            <i class="fas fa-reply mr-1"></i> {{ $testimonial->admin_response ? 'Edit Response' : 'Reply' }}
                        </button>

                        <div class="flex-grow"></div>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center transition">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </form>
                    </div>

                    <!-- INLINE REPLY FORM (Hidden by default, toggled via Alpine) -->
                    <div x-show="showReplyForm" x-transition class="mt-4 pt-4 border-t border-gray-100 bg-gray-50 -mx-6 -mb-6 p-6">
                        <form action="{{ route('admin.testimonials.reply', $testimonial) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Write Response</label>
                            <textarea name="admin_response" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary mb-3" placeholder="Thank the customer for their feedback...">{{ $testimonial->admin_response }}</textarea>
                            
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showReplyForm = false" class="text-gray-500 text-sm hover:text-gray-700 px-3">Cancel</button>
                                <button type="submit" class="bg-primary text-white text-sm font-bold px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                                    Post Response
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-300">
                <div class="text-gray-400 text-4xl mb-3"><i class="far fa-comments"></i></div>
                <p class="text-gray-500 font-medium">No testimonials yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $testimonials->links() }}
    </div>

</div>

@endsection
