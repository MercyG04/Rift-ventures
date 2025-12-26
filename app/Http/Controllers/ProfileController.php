<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the User Dashboard (Profile + Bookings + Wishlist).
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // 1. Fetch Bookings (If you have a bookings relationship)
        // $bookings = $user->bookings()->latest()->get(); 
        // For now, passing empty array if relationship isn't ready
        $bookings = $user->bookings ?? []; 

        // 2. Fetch Wishlist (If you have a wishlist relationship)
        
        $wishlist = $user->wishlist()->orderBy('wishlist.created_at', 'desc')->get();

        // 3. Return the 'profile.dashboard' view with all this data
        return view('profile.dashboard', [
            'user' => $user,
            'bookings' => $bookings,
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Redirect back to the dashboard with success message
        return Redirect::route('profile.dashboard')->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Your account has been deleted.');
    }
}