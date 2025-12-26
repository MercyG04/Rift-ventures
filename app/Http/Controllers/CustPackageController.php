<?php

namespace App\Http\Controllers;

use App\Models\SafariPackage;
use App\Enums\PackageType;
use App\Enums\PackageCategory;
use App\Models\PackageVariant;
use Illuminate\Http\Request;

class CustPackageController extends Controller
{
    /**
     * 1. LOCAL TOURS PAGE
     * Logic: Type is Local AND Category is (Destination OR Getaway).
     * Excludes Safaris.
     */
    public function local()
    {
        $packages = SafariPackage::active()
            ->where('type', PackageType::LOCAL)
            ->whereIn('category', [PackageCategory::DESTINATION, PackageCategory::GETAWAY]) // The specific filter
            ->latest()
            ->paginate(12);

        return view('customer.packages.index', [
            'packages' => $packages,
            'pageTitle' => 'Local Tours & Getaways',
            'pageSubtitle' => 'Discover the beauty of our local destinations and weekend escapes.'
        ]);
    }

    /**
     * 2. INTERNATIONAL TOURS PAGE
     * Logic: Type is International AND Category is (Destination OR Getaway).
     * Excludes Safaris.
     */
    public function international()
    {
        $packages = SafariPackage::active()
            ->where('type', PackageType::INTERNATIONAL)
            ->whereIn('category', [PackageCategory::DESTINATION, PackageCategory::GETAWAY]) // The specific filter
            ->latest()
            ->paginate(12);

        return view('customer.packages.index', [
            'packages' => $packages,
            'pageTitle' => 'International Destinations',
            'pageSubtitle' => 'Experience the world with our curated international packages.'
        ]);
    }

    /**
     * 3. SAFARIS PAGE (The "Wildlife" Page)
     * Logic: Category must be SAFARI.
     * Includes BOTH Local and International types.
     */
    public function safaris()
    {
        $packages = SafariPackage::active()
            ->where('category', PackageCategory::SAFARI) // The only rule that matters here
            ->latest()
            ->paginate(12);

        return view('customer.packages.index', [
            'packages' => $packages,
            'pageTitle' => 'Wildlife Safaris',
            'pageSubtitle' => 'Witness the majesty of the wild in Kenya and beyond.'
        ]);
    }

    public function offers()
    {
        $packages = SafariPackage::active()
            ->where('is_special_offer', true)
            ->latest()
            ->paginate(12);

        return view('customer.packages.index', [
            'packages' => $packages,
            'pageTitle' => 'Special Offers & Deals',
            'pageSubtitle' => 'Grab these limited-time deals before they are gone!'
        ]);
    }

    /**
     * 4. SINGLE PACKAGE DETAILS
     * This is where a user lands after clicking "View More" on ANY card 
     * (Home page, Local page, or Safari page).
     */
    public function show($slug)
    {
        $package = SafariPackage::active()
            ->with('variants') // Eager load the hotel options
            ->where('slug', $slug)
            ->firstOrFail();

        return view('customer.packages.show', compact('package'));
    }

    /**
     * 5. SINGLE VARIANT DETAILS (The new method)
     * 
     */
   public function showVariant(SafariPackage $package, PackageVariant $variant)
    {
        // 1. Eager load the parent 'safariPackage' relation.
        // We need the parent package to get the Currency and main Itinerary.
        $variant->load('safariPackage');

        // 2. Security Check: Ensure the parent package is actually active.
        // Even if the variant exists, if the trip is 'inactive', we shouldn't show it.
        if (!$variant->safariPackage->is_active) {
            abort(404);
        }

        // 3. Return the specific variant view.
        // We pass both the variant (child) and package (parent) for easy access in the view.
        return view('customer.packages.variantdetails', [
            'variant' => $variant,
            'package' => $variant->safariPackage, 
        ]);
    }
    
    // --- API METHODS FOR SEARCH ---
    
    public function searchLive(Request $request)
    {
        // 1. Start with Active Packages only
        // We use where('is_active', true) instead of active() scope to be safe
        $query = SafariPackage::where('is_active', true);

        // 2. Apply Location Filter
        if ($request->filled('location')) {
            $term = $request->input('location');
            $query->where(function($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('location', 'like', "%{$term}%");
            });
        }

        // 3. Apply Duration Filter (Optional)
        if ($request->filled('duration')) {
            // Logic: Find packages with duration matching roughly or simple string match
            // For simple string match:
            $query->where('duration', 'like', "%{$request->duration}%");
        }

        // 4. Fetch Results (Limit to 5 for speed)
        $results = $query->latest()
                         ->select('id', 'title', 'slug', 'featured_image_path', 'price', 'location', 'currency')
                         ->take(5)
                         ->get();
        
        // 5. Return JSON
        return response()->json(['results' => $results]);
    }
    
    /**
     * FULL SEARCH PAGE: Returns the HTML view with all results.
     * Route: /search
     */
    public function search(Request $request)
    {
        $query = SafariPackage::where('is_active', true);

        // Re-apply filters for the full page
        if ($request->filled('location')) {
            $term = $request->input('location');
            $query->where(function($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('location', 'like', "%{$term}%");
            });
        }
        
        if ($request->filled('duration')) {
             $query->where('duration', 'like', "%{$request->duration}%");
        }

        if ($request->filled('travelers')) {
            // Logic for travelers if you have capacity fields, otherwise ignore
        }

        $packages = $query->paginate(12);

        return view('packages.index', [
             'packages' => $packages,
             'pageTitle' => 'Search Results',
             'pageSubtitle' => $packages->total() . ' results matching your criteria.'
        ]);
    }
}
