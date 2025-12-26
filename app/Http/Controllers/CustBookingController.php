<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SafariPackage;
use App\Models\PackageVariant;
use App\Enums\BookingStatus;
use app\Notifications\BookingInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Throwable;

use App\Notifications\BookingConfirmed; // For the secure traveler link
use App\Notifications\BookingCancelled; // For the cancellation email

class CustBookingController extends Controller
{
    

    
    // --- PHASE 1: SHOWING THE FORM (WITH PRE-FILLED VARIANT) ---
    public function create(Request $request, SafariPackage $package)
    {
        // 1. Check if the user clicked "Book Now" on a specific variant card
        $selectedVariant = null;
        
        if ($request->has('variant_id')) {
            $selectedVariant = PackageVariant::where('safari_package_id', $package->id)
                ->find($request->variant_id);
        }

        // Fallback: If no variant is selected, load all variants for the user to pick one
        $package->load('variants');

        // 2. Extract Duration Integer (for the frontend date calculator)
        $durationInt = (int) filter_var($package->duration, FILTER_SANITIZE_NUMBER_INT);
        if ($durationInt < 1) $durationInt = 1; // Default to 1 day

        return view('customer.booking', compact('package', 'selectedVariant', 'durationInt'));
    }

    // --- PHASE 2: STORING THE "DRAFT" / INITIAL BOOKING ---
    public function store(Request $request, SafariPackage $package)
    {
        // 1. Validate Input (Using new Adults/Children and Contact details)
        $validated = $request->validate([
            'booking_date' => 'required|date|after:today',
            'num_adults' => 'required|integer|min:1', 
            'num_children' => 'nullable|integer|min:0',
            'contact_email' => 'required|email',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'variant_id' => 'required|exists:package_variants,id',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // 2. Calculation & Limit Check
        $totalTravelers = $validated['num_adults'] + ($validated['num_children'] ?? 0);
        $min = $package->min_travelers ?? 1;
        $max = $package->max_travelers ?? 50;

        if ($totalTravelers < $min || $totalTravelers > $max) {
            return back()->withErrors(['num_adults' => "Total travelers must be between $min and $max."]);
        }

        // Get Price from the Selected Variant
        $variant = PackageVariant::findOrFail($request->variant_id);
        $pricePerPerson = $variant->price;
        $totalPrice = $pricePerPerson * $totalTravelers;

        // 3. Prepare Data for Saving/Session
        $bookingData = [
            'safari_package_id' => $package->id,
            'package_variant_name' => $variant->name,
            'booking_date' => $validated['booking_date'],
            'num_travelers' => $totalTravelers,
            'total_price' => $totalPrice,
            'status' => BookingStatus::PENDING,
           
            // Include contact details and traveler breakdown in special requests (for the booking record)
            'special_requests' => "Contact Name: {$validated['contact_name']}, {$validated['contact_phone']}. Adults: {$validated['num_adults']}, Children: {$validated['num_children']}. \n" . $validated['special_requests'],
        ];

        // 4. THE AUTH GATEKEEPER 🛡️
        if (!Auth::check()) {
            session()->put('pending_booking', $bookingData);
            return redirect()->route('register')
                ->with('info', 'Please create an account to complete your booking.');
        }

        // 5. Create Record
        $booking = $this->createBookingRecord(Auth::user()->id, $bookingData);
     

        $request->user()->notify(new BookingInvoice($booking));
      
        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking initiated! Please check your email for the invoice and payment instructions.');
    }
    

    // --- PHASE 2.5: RESUMING AFTER LOGIN ---
    public function resume()
    {
        if (!session()->has('pending_booking')) {
            return redirect()->route('home');
        }

        $data = session()->get('pending_booking');
        $booking = $this->createBookingRecord(Auth::user()->id, $data);
        session()->forget('pending_booking');
        Auth::user()->notify(new BookingInvoice($booking));


        return redirect()->route('customer.bookingcheckout', $booking);
    }

    // --- PHASE 3: PAYMENT / CHECKOUT PAGE ---
    public function checkout(Booking $booking)
    {
        // Security check
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }

        // If booking is Confirmed (paid), show success/details page, not checkout
        if ($booking->status === BookingStatus::CONFIRMED) {
            return redirect()->route('bookings.show', $booking);
        }

        return view('bookings.checkout', compact('booking'));
    }

    // --- PHASE 5: BOOKING HISTORY & CANCELLATION ---
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('safariPackage')
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->load(['safariPackage', 'payments', 'travelers']); // check if 'travelers' is the relationship name

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === BookingStatus::COMPLETED) {
            return back()->with('error', 'Cannot cancel a completed trip.');
        }
        
        try {
             DB::transaction(function () use ($booking) {
                $booking->update(['status' => BookingStatus::CANCELLED]);

                // Send Cancellation Email with T&Cs and Refund Info
                Auth::user()->notify(new BookingCancelled($booking));
            });

            return back()->with('success', 'Booking cancelled. Please check your email for refund details and terms & conditions.');

        } catch (Throwable $e) {
            logger()->error("Booking cancellation failed for ID {$booking->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to process cancellation. Please try again or contact support.');
        }
    }

    // --- PRIVATE HELPER ---
    private function createBookingRecord($userId, $data)
    {
        // Wrapper for database creation, ensures clean records
        return Booking::create(array_merge($data, ['user_id' => $userId]));
    }
}