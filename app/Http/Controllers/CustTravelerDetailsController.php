<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TravelService;
use App\Models\TravelerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CustTravelerDetailsController extends Controller
{
    //
    // DOOR 1: BOOKINGS (Standard Packages)
    // 
    public function edit(Request $request, Booking $booking)
    {
        // Reuse the shared logic, telling it this is a 'booking'
        return $this->loadView($booking, 'booking');
    }

    public function update(Request $request, Booking $booking)
    {
        // Reuse the shared logic, telling it to save to 'booking_id'
        return $this->processUpdate($request, $booking, 'booking_id', 'bookings.show');
    }


    // =========================================================
    // DOOR 2: SERVICE INQUIRIES (Custom/Visas)
    // =========================================================
    public function editService(Request $request, TravelService $service)
    {
        // Reuse the shared logic, telling it this is a 'service'
        return $this->loadView($service, 'service');
    }

    public function updateService(Request $request, TravelService $service)
    {
        // Reuse the shared logic, telling it to save to 'travel_service_id'
        // Redirect to home since services don't have a public 'show' page
        return $this->processUpdate($request, $service, 'travel_service_id', 'home');
    }


    // =========================================================
    // SHARED LOGIC (The Brain) 🧠
    // =========================================================

    /**
     * Shared method to load the view for either model.
     */
    private function loadView($model, $type)
    {
        // 1. Determine Count
        // Bookings use 'num_travelers', Services use 'no_of_travellers' (or sum of adults+kids)
        $count = $model->num_travelers 
              ?? $model->no_of_travellers 
              ?? ($model->adults + $model->children);

        // 2. Load Existing Data
        $existingTravelers = $model->travelers()->get();

        // 3. Determine Post Route
        $postRoute = $type === 'booking' 
            ? route('bookings.travelers.update', $model->id)
            : route('services.travelers.update', $model->id);

        return view('services.travelers.edit', [
            'parentModel' => $model,
            'totalTravelers' => $count,
            'existingTravelers' => $existingTravelers,
            'postRoute' => $postRoute // Passes the correct URL to the view
        ]);
    }

    /**
     * Shared method to validate and save data.
     */
    private function processUpdate(Request $request, $model, $foreignKeyColumn, $redirectRoute)
    {
        // 1. Calculate count
        $expectedCount = $model->num_travelers 
                      ?? $model->no_of_travellers 
                      ?? ($model->adults + $model->children);

        // 2. Validate
        $validatedData = $request->validate([
            'travelers' => ['required', 'array', 'size:' . $expectedCount],
            'travelers.*.full_name' => ['required', 'string', 'max:255'],
            'travelers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'travelers.*.email' => ['nullable', 'email'],
            
            // "Either/Or" Rule for Passport vs ID
            'travelers.*.id_number' => ['nullable', 'string', 'max:50', 'required_without:travelers.*.passport_number'],
            'travelers.*.passport_number' => ['nullable', 'string', 'max:50', 'required_without:travelers.*.id_number'],
            'travelers.*.passport_expiry' => ['nullable', 'date', 'after:today', 'required_with:travelers.*.passport_number'],
        ]);

        try {
            DB::transaction(function () use ($model, $validatedData, $foreignKeyColumn) {
                // Delete old records (Clean slate)
                $model->travelers()->delete();

                // Create new ones
                foreach ($validatedData['travelers'] as $index => $data) {
                    TravelerDetail::create([
                        $foreignKeyColumn => $model->id, // Saves to booking_id OR travel_service_id
                        'full_name' => $data['full_name'],
                        'email' => $data['email'] ?? null,
                        'is_primary_contact' => ($index === 0),
                        'id_number' => $data['id_number'] ?? null,
                        'passport_number' => $data['passport_number'] ?? null,
                        'date_of_birth' => $data['date_of_birth'],
                        'passport_expiry' => $data['passport_expiry'] ?? null,
                    ]);
                    // Encryption is automatic via Model Casts
                }
            });

            // Redirect with success message
            if ($redirectRoute === 'home') {
                 return redirect()->route('home')->with('success', 'Traveler details submitted securely. We will process your documents shortly.');
            }
            return redirect()->route($redirectRoute, $model)->with('success', 'Traveler details submitted securely.');

        } catch (Throwable $e) {
            \Log::error("Traveler PII Update Failed: " . $e->getMessage());
            return back()->with('error', 'System error saving details. Please try again.');
        }
    }
}