<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TravelService;
use App\Enums\ServiceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdmTravelServiceController extends Controller
{
    /**
     * List all service inquiries (Leads).
     */
    public function index()
    {
        // Simple paginated list, newest first
        $inquiries = TravelService::latest()->paginate(20);
        
        return view('admin.services.index', compact('inquiries'));
    }

    /**
     * View specific details of a request.
     * The view will display the JSON 'additional_details' (Flight info, Visa country, etc.)
     */
    public function show(TravelService $service)
    {
        $securePiiLink = URL::temporarySignedRoute(
        'services.travelers.edit', // We will create this route
        now()->addDays(7),
        ['service' => $service->id]
    );
        return view('admin.services.show', compact('service'));
    }

    /**
     * Update the status.
     * This is purely for the Admin's records (CRM).
     * It does NOT send emails.
     */
    public function update(Request $request, TravelService $service)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,contacted,booked,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Convert string to Enum if you are using the Enum class, 
        // or just save the string directly if you prefer absolute simplicity.
        // We will stick to the Enum for database consistency.
        $newStatus = ServiceStatus::tryFrom($validated['status']);

        try {
            DB::transaction(function () use ($service, $validated, $newStatus) {
                $service->update([
                    'status' => $newStatus,
                    'admin_notes' => $validated['admin_notes']
                ]);
            });

            return back()->with('success', 'Inquiry updated. Remember to contact the client manually via email/phone.');

        } catch (Throwable $e) {
            return back()->with('error', 'Failed to update status.');
        }
    }

    /**
     * Delete a request (Cleanup).
     */
    public function destroy(TravelService $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Inquiry deleted.');
    }
}