<?php

namespace App\Http\Controllers;

use App\Models\SafariPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


 /* Controller for managing the user's personal wishlist of Safari Packages.
 * This entire controller is protected by the 'auth' middleware 
 * to ensure only logged-in users can manage their wishlist.
 */
class WishlistController extends Controller
{
    public function index()
    {
        // 1. Retrieve the authenticated user's wishlist relationship.
        // The user model's 'wishlist' method is defined as a BelongsToMany
        // relationship linked to the 'safari_package_id' via the 'wishlist' pivot table.
        $wishlist = Auth::user()->wishlist()->with('latestImage')->get();

        // 2. Return the view with the list of packages.
        return view('wishlist.index', compact('wishlist'));
    }

    /**
     * Toggles a Safari Package in or out of the authenticated user's wishlist.
     * This uses Laravel's highly efficient toggle() method for many-to-many relationships.
     *
     * @param SafariPackage $package The package to be toggled.
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(SafariPackage $package)
    {
        // Get the authenticated user
        $user = Auth::user();

        // 1. Execute the toggle operation.
        // $user->wishlist() refers to the BelongsToMany relationship.
        // The toggle() method checks if the safari_package_id exists in the 
        // 'wishlist' pivot table for this user.
        // - If it exists, it DETACHES (removes) it.
        // - If it does NOT exist, it ATTACHES (adds) it.
        
        // toggle() returns an array containing 'attached' and 'detached' IDs.
        $toggleResult = $user->wishlist()->toggle($package->id);

        // 2. Determine the action for the response message.
        $isAttached = count($toggleResult['attached']) > 0;
        
        $message = $isAttached 
            ? "{$package->title} added to your wishlist."
            : "{$package->title} removed from your wishlist.";

        // 3. Return a JSON response for frontend AJAX/AlpineJS interaction.
        return response()->json([
            'success' => true,
            'action' => $isAttached ? 'added' : 'removed',
            'message' => $message,
        ]);
    }
}
