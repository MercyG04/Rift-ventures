@extends('layouts.Adminmasterlayout')

@section('content')

<div class="max-w-3xl mx-auto pb-20">
    
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800">Add Manual Testimonial</h1>
        <a href="{{ route('admin.testimonials.index') }}" class="text-gray-500 hover:text-gray-700 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md p-8 border-t-4 border-primary">
        <p class="text-sm text-gray-500 mb-6 bg-blue-50 p-3 rounded-lg border border-blue-100">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Use this form to add reviews received via email, WhatsApp, or in person. These will be <strong>auto-approved</strong>.
        </p>

        <form action="{{ route('admin.testimonials.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Author Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Customer Name</label>
                    <input type="text" name="author_name" value="{{ old('author_name') }}" 
                           class="w-full bg-gray-50 border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary" 
                           placeholder="e.g. John Doe" required>
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Rating (Stars)</label>
                    <select name="rating" class="w-full bg-gray-50 border-gray-300 rounded-lg h-12 px-4 focus:ring-primary focus:border-primary">
                        <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                        <option value="3">⭐⭐⭐ (3 Stars)</option>
                        <option value="2">⭐⭐ (2 Stars)</option>
                        <option value="1">⭐ (1 Star)</option>
                    </select>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Testimonial Content</label>
                    <textarea name="content" rows="5" 
                              class="w-full bg-gray-50 border-gray-300 rounded-lg p-4 focus:ring-primary focus:border-primary" 
                              placeholder="Paste the customer's review here..." required>{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-lg shadow hover:bg-blue-800 transition">
                    Save & Publish
                </button>
            </div>
        </form>
    </div>

</div>

@endsection