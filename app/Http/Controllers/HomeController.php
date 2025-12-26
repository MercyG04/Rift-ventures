<?php

namespace App\Http\Controllers;

use App\Models\SafariPackage;
use App\Models\Testimonial;
use App\Enums\PackageType;
use App\Enums\PackageCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {   
        // 1. HERO CAROUSEL (Admin Picked)
        // Shows on the big slider at the top.
        $heroPackages = SafariPackage::active()
             ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        // 2. SPECIAL OFFERS (Admin Picked)
        // Shows in the "Hot Deals" banner.
        $specialOffers =  SafariPackage:: active()
            ->where('is_special_offer', true)
            ->latest()
            ->take(3) // Usually we only show a few hot deals
            ->get();

        // 3. POPULAR LOCAL DESTINATIONS (e.g., Nyali, Diani)
        // Logic: Local + Destination + Sorted by most variants (Hotels)
        $popularDestinations = SafariPackage:: active()
            ->where('type', PackageType::LOCAL)
            ->where('category', PackageCategory::DESTINATION) // Strict Category Check
            ->withCount('variants')
            ->orderBy('variants_count', 'desc') // "Diani" (15 hotels) shows before "Kisumu" (2 hotels)
            ->take(6)
            ->get();

        // 4. WEEKEND GETAWAYS (e.g., Nanyuki Cabins, Naivasha)
        // Logic: Local + Getaway
        $weekendGetaways = SafariPackage:: active()
            ->where('category', PackageCategory::GETAWAY)
            ->where('category', PackageCategory::GETAWAY) // Strict Category Check
            ->latest()
            ->take(6)
            ->get();

        // 5. INTERNATIONAL TRIPS
        // Logic: Type is International
        $internationalPackages = SafariPackage:: active() 
            ->where('type', PackageType::INTERNATIONAL)
            ->latest()
            ->take(6)
            ->get();

        // 6. TESTIMONIALS
        $testimonials = Testimonial::where('is_approved', true)
            ->latest()
            ->take(6)
            ->get();
// home view
        return view('customer.Home', compact(
            'heroPackages',
            'specialOffers',
            'popularDestinations',
            'weekendGetaways',
            'internationalPackages',
            'testimonials'
        ));
    }
}