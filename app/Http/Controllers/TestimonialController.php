<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display a listing of approved testimonials (Public "Read All" Page).
     */
    public function index()
    {
        // Fix: Use pagination so we can show many reviews, not just 5.
        // Also return a View, not JSON.
        $testimonials = Testimonial::where('is_approved', true)
            ->latest()
            ->paginate(12);

        return view('customer.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return view('customer.testimonials.create');
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => ['required', 'string', 'min:20', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'author_name' => ['nullable', 'string', 'max:255'], 
        ]);

        $user = Auth::user();

        Testimonial::create([
            'user_id' => $user->id,
            'author_name' => $request->input('author_name') ?? $user->name,
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'is_approved' => false, 
        ]);

        return redirect()->route('home')
            ->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }
}