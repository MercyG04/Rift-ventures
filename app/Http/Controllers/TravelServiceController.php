<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TravelService;
use App\Models\TravelerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TravelServiceController extends Controller
{
    /**
     * Display the "Seamless Travel" Hub.
     * This view contains the Tabs: Flight, Passport, Visa, Custom Trip.
     */
    public function index()
    {
        // View simply loads. 
        // If coming from 'resume()', the session will have '_old_input' flashed, 
        // so Blade's old('field_name') will automatically populate the tabs.
        return view('customer.services.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * The Resume Route (GET /plan-your-trip/resume)
     * Your AuthController should redirect here after registration if 'pending_service_request' exists.
     */
    public function resume()
    {
        // 1. Safety Check: Is there actually data waiting?
        if (!session()->has('pending_service_request')) {
            return redirect()->route('customer.services.index');
        }

        // 2. Flash the data to Laravel's special '_old_input' session key.
        // This makes the data available to the old() helper in the view, just like a validation error.
        session()->flash('_old_input', session('pending_service_request'));

        // 3. Send them to the form with a helpful message
        return redirect()->route('customer.services.index')
            ->with('info', 'Welcome back! We have restored your details. Please review and click Submit.');
    }

    /**
     * Handle the Form Submission (for ALL tabs).
     */
    public function store(Request $request)
    {
        // --- 1. THE AUTH WALL ---
        if (!Auth::check()) {
            // Save the entire request payload to session
            session(['pending_service_request' => $request->all()]);
            
            // Redirect to Register
            // Note: You will handle the redirect back to 'services.resume' in your AuthController
            return redirect()->route('register')
                ->with('warning', 'To secure your travel documents and details, please create an account first. We have saved your progress.');
        }

        // --- 2. VALIDATION ---
        $validated = $request->validate([
            'service_type'     => 'required|in:flight,passport,visa,custom_trip',
            
            // Contact Info (Required)
            'contact_name'     => 'required|string|max:100',
            'contact_email'    => 'required|email|max:255',
            'contact_phone'    => 'required|string|max:20',
            
            // Core Details (Nullable keys, as they depend on the Tab selected)
            'destination'      => 'nullable|string|max:255',
            'travel_date'      => 'nullable|date|after_or_equal:today',
            'duration'         => 'nullable|string|max:50',
            'no_of_travellers' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            // --- 3. PREPARE FLEXIBLE DATA ---
            // Separate the "Standard" columns from the "Service Specific" details
            $standardFields = [
                '_token', 'service_type', 'contact_name', 'contact_email', 'contact_phone',
                'destination', 'travel_date', 'duration', 'no_of_travellers', 'special_requests'
            ];
            
            // "Everything else" (Airline pref, Passport Type, Visa Country) goes into JSON
            $jsonPayload = $request->except($standardFields);

            // Generate Reference Number (e.g., VIS-240125-ABCD)
            $prefix = match($validated['service_type']) {
                'flight' => 'FLT',
                'passport' => 'PPT',
                'visa' => 'VIS',
                default => 'TRP',
            };
            $ref = $prefix . '-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // --- 4. SAVE SERVICE RECORD ---
            $service = new TravelService();
            $service->user_id = Auth::id(); // Confirmed User
            $service->reference_number = $ref;
            $service->contact_name = $validated['contact_name'];
            $service->contact_email = $validated['contact_email'];
            $service->contact_phone = $validated['contact_phone'];
            $service->service_type = $validated['service_type'];
            
            // Standard Columns
            $service->destination = $validated['destination'] ?? null;
            $service->travel_date = $validated['travel_date'] ?? null;
            $service->duration = $validated['duration'] ?? null;
            $service->no_of_travellers = $validated['no_of_travellers'];
            $service->admin_notes = $validated['special_requests']; // Initial notes from user
            
            // JSON Column
            $service->additional_details = json_encode($jsonPayload);
            
            $service->save();

            // --- 5. CREATE PRIMARY TRAVELER ---
            // We split the name to create the first Traveler record automatically
            $nameParts = explode(' ', $validated['contact_name'], 2);
           TravelerDetail::create([
                'travel_service_id' => $service->id,
                'is_primary_contact' => true,
                'first_name' => $nameParts[0],
                'last_name'  => $nameParts[1] ?? 'Traveler',
                'email'      => $validated['contact_email'],
                'phone'      => $validated['contact_phone'],
            ]);

            // Clear session if it existed
            session()->forget('pending_service_request');

            DB::commit();

            // --- 6. SUCCESS REDIRECT ---
            return redirect()->route('profile.dashboard')
                ->with('success', 'Request Received! Reference: ' . $ref . '. We will review your details and send you an invoice shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Travel Service Error: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Something went wrong processing your request. Please try again.');
        }
    }
}