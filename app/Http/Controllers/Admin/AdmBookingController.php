<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Notifications\BookingConfirmed;
use App\Notifications\BookingCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdmBookingController extends Controller
{
    /**
     * Display a paginated, filterable list of all bookings.
     */
    public function index(Request $request)
    {
        // 1. Eager Load relationships for performance (N+1 prevention)
        $bookings = Booking::with(['user:id,name,email', 'safariPackage:id,title'])
            ->latest();

        // 2. Status Filtering (e.g., ?status=PENDING)
        if ($request->filled('status') && $request->status !== 'ALL') {
            $status = strtoupper($request->status);
            if (BookingStatus::tryFrom($status)) {
                $bookings->where('status', $status);
            }
        }

        // 3. Search (Example: by Customer Name or Booking ID)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $bookings->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                      ->orWhereHas('user', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', $searchTerm);
                      });
            });
        }

        $bookings = $bookings->paginate(20);

        // Pass the BookingStatus enum values for filtering dropdowns
        $statuses = BookingStatus::cases();

        return view('admin.booking.index', compact('bookings', 'statuses'));
    }

    /**
     * Display detailed information for a single booking.
     */
    public function show(Booking $booking)
    {
        // Load deep relationships for the detailed view
        $booking->load(['user', 'safariPackage', 'travelerDetails', 'payments']);

        // Check if traveler details are present
        $travelersComplete = $booking->travelers->count() > 0;
        
        return view('admin.booking.show', compact('booking', 'travelersComplete'));
    }

    /**
     * Handle manual status updates (Exception Management).
     * Uses DB Transaction to ensure status is updated cleanly.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_column(BookingStatus::cases(), 'value')),
        ]);
        
        $newStatus = BookingStatus::from($validated['status']);

        try {
            DB::transaction(function () use ($booking, $newStatus) {
                // Update the status
                $booking->update(['status' => $newStatus]);
                
                // If the Admin confirms payment, send the secure link email
                if ($newStatus === BookingStatus::CONFIRMED) {
                    $booking->user->notify(new BookingConfirmed($booking));
                }
            });

            return back()->with('success', "Booking #{$booking->id} status successfully updated to {$newStatus->value}.");

        } catch (Throwable $e) {
            logger()->error("Admin failed to update booking status {$booking->id}: " . $e->getMessage());
            return back()->with('error', 'Failed to update status due to a system error.');
        }
    }

    /**
     * Resends the Booking Confirmation Email (secure link).
     */
    public function resendConfirmation(Booking $booking)
    {
        // Check if the booking is even in a state to be confirmed
        if ($booking->status === BookingStatus::CANCELLED) {
            return back()->with('error', 'Cannot confirm a cancelled booking.');
        }

        // The Notification (BookingConfirmed) handles sending the secure link
        $booking->user->notify(new BookingConfirmed($booking));

        return back()->with('success', 'Confirmation and Traveler Details email successfully resent.');
    }

    /**
     * Resends the Cancellation Email.
     */
    public function resendCancellation(Booking $booking)
    {
        // Only resend if the booking is actually cancelled
        if ($booking->status !== BookingStatus::CANCELLED) {
            return back()->with('error', 'Booking must be cancelled before resending the cancellation email.');
        }

        // The Notification (BookingCancelled) contains refund details
        $booking->user->notify(new BookingCancelled($booking));

        return back()->with('success', 'Cancellation and Refund Details email successfully resent.');
    }
}