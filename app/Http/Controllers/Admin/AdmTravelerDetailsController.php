<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TravelerDetail;
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
        $travelers = $booking->travelers()->get();
        
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
    public function update(Request $request, Booking $booking)
    {
        // ACCESS GRANTED BY MIDDLEWARE: No internal authorization check needed.

        // 1. Data Integrity Validation: 
        // Even with an admin, we must enforce required fields to maintain data quality.
        $validatedData = $request->validate([
            // Ensure the input array size matches the number of travelers for this booking.
            'travelers' => ['required', 'array', 'size:' . $booking->num_travelers], 
            'travelers.*.full_name' => ['required', 'string', 'max:255'],
            'travelers.*.date_of_birth' => ['required', 'date', 'before:tomorrow'], 
            
            // PII Requirements: At least one ID type must be provided.
            'travelers.*.id_number' => ['nullable', 'string', 'max:50', 'required_without:travelers.*.passport_number'],
            'travelers.*.passport_number' => ['nullable', 'string', 'max:50', 'required_without:travelers.*.id_number'],
            
            // Passport expiry is only required if a passport number is given.
            'travelers.*.passport_expiry' => ['nullable', 'date', 'after:today', 'required_with:travelers.*.passport_number'],
        ]);

        try {
            // 2. Database Transaction: Essential for sensitive, multi-step operations.
            // If any part of the process fails (e.g., saving one traveler), 
            // the entire change is rolled back, preventing partial data corruption.
            DB::transaction(function () use ($booking, $validatedData) {
                
                // A. Delete Existing PII: 
                // We wipe the old records before saving new ones to handle changes 
                // in the number of travelers (though unlikely for Admin edits).
                $booking->travelers()->delete();

                // B. Re-Save New PII:
                foreach ($validatedData['travelers'] as $index => $travelerData) {
                    $traveler = new TravelerDetail(array_merge($travelerData, [
                        'booking_id' => $booking->id,
                        // Set the first traveler in the list as the primary contact.
                        'is_primary_contact' => ($index === 0), 
                    ]));
                    
                    // The Model's Mutators automatically encrypt the PII 
                    // (ID/Passport) fields just before they are saved to the database.
                    $traveler->save(); 
                }
            });

            // 3. Success Feedback
            return back()->with('success', 'Traveler details successfully updated and re-encrypted by administrator.');

        } catch (Throwable $e) {
            // 4. Error Logging and Feedback
            // Log the detailed error message for debugging purposes.
            logger()->error("Admin PII update failed for booking {$booking->id}: " . $e->getMessage());
            
            // Provide a non-specific error message to the user.
            return back()->with('error', 'A system error occurred during the PII update. Please check the logs.');
        }
    }
}
