<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TravelerDetail;
use App\Notifications\BookingConfirmed;
use Illuminate\Support\Facades\log;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Throwable;

class AdmTravelerDetailsController extends Controller
{
    
    public function show(Booking $booking)
    {
        // ACCESS GRANTED BY MIDDLEWARE: No internal authorization check needed.

        // 1. Load Decrypted Data:
        // When travelers are retrieved through the relationship, the TravelerDetail 
        // model's Casts (mutators) automatically decrypt the PII fields (like 
        // passport/ID numbers) because the 'travelers' relationship is loaded.
        $travelers = $booking->travelers()->get();

        // 2. Pass data to the Admin view.
        return view('admin.travelers.show', compact('booking', 'travelers'));
    }

   /**
     * Display the form for the Admin to manually edit or enter PII.
     * * @param Booking $booking The booking model being edited.
     * @return \Illuminate\View\View
     */
    public function edit(Booking $booking)
    {
        // ACCESS GRANTED BY MIDDLEWARE: No internal authorization check needed.
        
        // 1. Load existing traveler data (which is decrypted for display in the form).
        $travelers = $booking->travelerDetails()->get();
        
        // 2. Get the expected number of forms based on the booking.
        $numForms = $booking->num_travelers;

        // 3. Pass data to the Admin edit view.
        return view('admin.travelers.edit', compact('booking', 'travelers', 'numForms'));
    }
    /**
     * Handle the manual submission/correction of PII by an Admin 
     * @param Request $request Standard Laravel Request object.
     * @param Booking $booking The booking model.
     */
    public function resendRequest(Request $request, Booking $booking)
    {
        try {
            // 1. Unlock the Form (Reset the 48-hour timer)
            // We "touch" the timestamps of existing records. 
            // The Customer Controller checks 'updated_at' to enforce the lock.
            // Setting it to NOW() gives them a fresh 48-hour window.
            if ($booking->travelerDetails()->exists()) {
                $booking->travelerDetails()->touch();
                Log::info("Admin unlocked PII form for Booking ID: {$booking->id}");
            }

            // 2. Resend the Notification
            // Assuming BookingConfirmed contains the link to the PII form.
            $booking->user->notify(new BookingConfirmed($booking));

            Log::info("Admin resent PII request email for Booking ID: {$booking->id}");

            return back()->with('success', 'Secure link resent to customer. The editing window has been reopened for 48 hours.');

        } catch (Throwable $e) {
            // Log the specific error for the developer
            Log::error("Failed to resend PII link for Booking {$booking->id}: " . $e->getMessage());

            // Generic error for the admin interface
            return back()->with('error', 'System error: Could not resend the link. Please check system logs.');
        }
    }
}    
