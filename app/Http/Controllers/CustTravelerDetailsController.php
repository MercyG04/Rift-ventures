<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TravelService;
use App\Models\TravelerDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Throwable;

class CustTravelerDetailsController extends Controller
{
    
 public static function middleware(): array
    {
        return [
            // 1. Force Login for all methods
            'auth',

            
            function ($request, $next) {
                $user = $request->user();
                if ($user && $user->role !== 'customer'){
                    abort(403, 'This area is restricted to customer accounts only.');
                }
                return $next($request);
            },
        ];
    }
   


    // BOOKINGS (Standard Packages)
     
    public function edit(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id)  {
            abort(403, 'Unauthorized access to this booking.');
        }
        return $this->loadView($booking, 'booking');
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id !== $request->user()->id)  {
            abort(403, 'Unauthorized access to this booking.');
        }
        return $this->processUpdate($request, $booking, 'booking_id');
    }


    
    // ERVICE INQUIRIES (Custom/Visas)
    
    public function editService(Request $request, TravelService $service)
    {
         if ($service->user_id !==$request->user()->id)  {
            abort(403, 'Unauthorized access to this service request.');
        }
        return $this->loadView($service, 'service');
    }

    public function updateService(Request $request, TravelService $service)
    {
         if ($service->user_id !== $request->user()->id)  {
            abort(403, 'Unauthorized access to this service request.');
        }
        return $this->processUpdate($request, $service, 'travel_service_id');
    }


    
    // SHARED LOGIC 
    

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
        $existingTravelers = $model->travelerDetails()->get();

        // 3. Determine Post Route
        $postRoute = $type === 'booking' 
            ? route('bookings.travelers.update', $model->id)
            : route('services.travelers.update', $model->id);

        return view('traveler.edit', [
            'parentModel' => $model,
            'totalTravelers' => $count,
            'existingTravelers' => $existingTravelers,
            'postRoute' => $postRoute // Passes the correct URL to the view
        ]);
    }

    /**
     * Shared method to validate and save data.
     */
    private function processUpdate(Request $request, $model, $foreignKeyColumn)
    {
        // 1. ENFORCE 48-HOUR LOCK (Server-Side)
        
        $existing = $model->travelerDetails()->first();
        
        if ($existing) {
            $hoursSinceUpdate = $existing->updated_at->diffInHours(now());
            
            if ($hoursSinceUpdate > 48) {
                // If they try to hack/force a post request after 48h, we block it here.
                return back()->with('error', 'Modification window has closed. Data is locked for processing.');
            }
        }

        // 2. Calculate Expected Count
        $expectedCount = $model->num_travelers 
                      ?? $model->no_of_travellers 
                      ?? ($model->adults + $model->children);

        // 3. Validate Inputs
        $validatedData = $request->validate([
            'travelers' => ['required', 'array', 'size:' . $expectedCount],
            'travelers.*.full_name' => ['required', 'string', 'max:255'],
            'travelers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'travelers.*.email' => ['nullable', 'email'],
            'travelers.*.id_number' => ['nullable', 'string', 'max:50', 'required_without:travelers.*.passport_number'],
            'travelers.*.passport_number' => ['nullable', 'string', 'max:50', 'required_without:travelers.*.id_number'],
            'travelers.*.passport_expiry' => ['nullable', 'date', 'after:today', 'required_with:travelers.*.passport_number'],
        ]);

        try {
            DB::transaction(function () use ($model, $validatedData, $foreignKeyColumn) {
               
                $model->travelerDetails()->delete();

                foreach ($validatedData['travelers'] as $index => $data) {
                    TravelerDetail::create([
                        $foreignKeyColumn => $model->id, 
                        'full_name' => $data['full_name'],
                        'email' => $data['email'] ?? null,
                        'is_primary_contact' => ($index === 0),
                        'id_number' => $data['id_number'] ?? null,
                        'passport_number' => $data['passport_number'] ?? null,
                        'date_of_birth' => $data['date_of_birth'],
                        'passport_expiry' => $data['passport_expiry'] ?? null,
                    ]);
                }
            });

            
            return back()->with('success', 'Traveler details submitted securely. You have 48 hours to make corrections if needed.');

        } catch (Throwable $e) {
            Log::error("Traveler PII Update Failed: " . $e->getMessage());
            return back()->with('error', 'System error saving details. Please try again or contact support.');
        }
    }
}