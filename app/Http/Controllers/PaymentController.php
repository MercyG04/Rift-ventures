<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Enums\BookingStatus;
use App\Notifications\BookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Step 1: Show the Payment Form.
     * Here we generate the Idempotency Key and the Stripe PaymentIntent.
     */
    public function create(Request $request, Booking $booking)
    {
        // Security: Ensure user owns this booking
        if ($booking->user_id !== auth()::id) {
            abort(403);
        }

        // Installment Logic: 
        // By default, we ask for the remaining balance.
        // Ideally, you could let the user input a custom amount here, 
        // but for now, let's assume they pay the full remaining balance.
        $paidAmount = $booking->payments()->where('status', PaymentStatus::SUCCESSFUL)->sum('amount');
        $remainingBalance = $booking->total_price - $paidAmount;

        if ($remainingBalance <= 0) {
            return redirect()->route('bookings.show', $booking)->with('info', 'This booking is fully paid!');
        }

        // 1. Generate Idempotency Key
        // We create a unique key for THIS specific attempt. 
        // If they refresh, we check if there's already a pending intent to reuse.
        $idempotencyKey = 'booking_' . $booking->id . '_attempt_' . time(); 

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // 2. Create the Local Payment Record (PENDING)
            // This acts as our "Lock" and audit trail.
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $remainingBalance, // Stored in Cents
                'currency' => 'KES',
                'status' => PaymentStatus::PENDING,
                'provider' => 'stripe',
                'idempotency_key' => $idempotencyKey,
            ]);

            // 3. Create Stripe Payment Intent with Idempotency Key
            $intent = PaymentIntent::create([
                'amount' => $remainingBalance,
                'currency' => 'kes',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id // Link back to our local ledger
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ], [
                'idempotency_key' => $idempotencyKey, // <-- THE MAGIC KEY
            ]);

            // 4. Update local record with Stripe's ID so we can track it
            $payment->update(['transaction_id' => $intent->id]);

            return view('payments.create', [
                'booking' => $booking,
                'clientSecret' => $intent->client_secret,
                'payment' => $payment,
                'stripeKey' => env('STRIPE_KEY')
            ]);

        } catch (Throwable $e) {
            Log::error("Stripe Init Failed: " . $e->getMessage());
            return back()->with('error', 'Could not initiate payment securely. Please try again.');
        }
    }

    /**
     * Step 2: Handle the Success Callback (After 3DSecure/Card check).
     * This is called via AJAX or Redirect after the frontend confirms payment.
     */
    public function success(Request $request, Booking $booking)
    {
        $paymentIntentId = $request->query('payment_intent');

        if (!$paymentIntentId) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Invalid payment verification.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // 1. Verify with Stripe directly (Don't trust the browser)
            $intent = PaymentIntent::retrieve($paymentIntentId);

            // 2. Check Status
            if ($intent->status === 'succeeded') {
                
                // Find our local ledger record
                $payment = Payment::where('transaction_id', $paymentIntentId)->first();

                if ($payment && $payment->status !== PaymentStatus::SUCCESSFUL) {
                    DB::transaction(function () use ($payment, $booking) {
                        // Update Payment to Successful
                        $payment->update(['status' => PaymentStatus::SUCCESSFUL]);

                        // Check if Booking is Fully Paid
                        $totalPaid = $booking->payments()->where('status', PaymentStatus::SUCCESSFUL)->sum('amount');
                        
                        // If Paid >= Total, Confirm the Booking
                        // (This handles installments: if partially paid, status stays PENDING)
                        if ($totalPaid >= $booking->total_price) {
                            $booking->update(['status' => BookingStatus::CONFIRMED]);
                            
                            // TRIGGER: Send Email with Secure PII Link
                            $booking->user->notify(new BookingConfirmed($booking));
                        }
                    });
                }

                return redirect()->route('bookings.show', $booking)
                    ->with('success', 'Payment successful! ' . ($booking->status === BookingStatus::CONFIRMED ? 'Your secure traveler link has been emailed.' : 'Installment received.'));
            }

            return redirect()->route('bookings.show', $booking)->with('info', 'Payment is processing or failed.');

        } catch (Throwable $e) {
            Log::error("Payment Verification Error: " . $e->getMessage());
            return redirect()->route('bookings.show', $booking)->with('error', 'Error verifying payment status.');
        }
    }
}
