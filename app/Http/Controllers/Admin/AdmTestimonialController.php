<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; 
use Throwable; 


class AdmTestimonialController extends Controller
{
    /**
     * Display a list of ALL testimonials, prioritized by unapproved ones.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all testimonials, ordering unapproved ones first.
        $testimonials = Testimonial::orderBy('is_approved', 'asc')
                                   ->latest()
                                   ->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Testimonial::create([
                    'user_id' => Auth::id(), // Linked to the Admin user who entered it
                    'author_name' => $request->author_name,
                    'content' => $request->input('content'),
                    'rating' => $request->rating,
                    'is_approved' => true, // Auto-approve admin entries
                ]);
            });

            return redirect()->route('admin.testimonials.index')->with('success', 'Manual testimonial created successfully.');
        } catch (Throwable $e) {
            Log::error("Failed to create manual testimonial: " . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create testimonial. Please try again.');
        }
    }

    public function reply(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request, $testimonial) {
                $testimonial->update([
                    'admin_response' => $request->admin_response,
                    'is_approved' => true, 
                ]);
            });

            return back()->with('success', 'Response posted successfully.');
        } catch (Throwable $e) {
            Log::error("Failed to post response for testimonial #{$testimonial->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to post response. Please try again.');
        }
    }

    /**
     * Approve a pending testimonial, making it visible on the homepage.
     * @param Testimonial $testimonial The testimonial to approve.
     * @return \Illuminate\Http\RedirectResponse
     */
     public function approve(Testimonial $testimonial)
    {
        try {
            DB::transaction(function () use ($testimonial) {
                $testimonial->update(['is_approved' => true]);
            });

            return back()->with('success', 'Testimonial approved.');
        } catch (Throwable $e) {
            Log::error("Failed to approve testimonial #{$testimonial->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to approve testimonial.');
        }
    }

    /**
     * Delete a testimonial, typically used for spam or inappropriate content.
     * @param Testimonial $testimonial The testimonial to delete.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            DB::transaction(function () use ($testimonial) {
                $testimonial->delete();
            });

            return back()->with('success', 'Testimonial deleted.');
        } catch (Throwable $e) {
            Log::error("Failed to delete testimonial #{$testimonial->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to delete testimonial.');
        }
    }
}
