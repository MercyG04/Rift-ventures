<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Notifications\VerifyNewsSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class NewsletterController extends Controller
{
    /**
     * Handles the initial subscription request (Step 1: User Input).
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        $subscription = null; // Initialize outside of try block for cleanup

        try {
            DB::transaction(function () use ($request, &$subscription) {
                // 1. Create the subscription record (Pending State)
                $subscription = NewsletterSubscription::create([
                    'email' => $request->email,
                    // 2. Generate a unique token for verification/unsubscribe
                    'unsubscribe_token' => Str::random(60), 
                    'is_verified' => false, // Default is false (unverified)
                ]);

                // 3. Send the verification email
                $subscription->notify(new VerifyNewsSubscription());
            });

            return back()->with('success', 'Success! Please check your email inbox (and spam folder) to confirm your subscription.');

        } catch (\Exception $e) {
            // Log error
            logger()->error("Newsletter subscription failed: " . $e->getMessage());
            
            // If the transaction fails, the record is rolled back, but we return a generic error.
            return back()->withInput()->with('error', 'There was an issue processing your subscription. Please try again.');
        }
    }

    /**
     * Handles the email verification click (Step 3: Confirmation).
     * This route MUST be signed, as defined in the Notification.
     */
    public function verify(Request $request)
    {
        // 1. Validate the token and signed URL parameters
        if (!$request->hasValidSignature()) {
            abort(401, 'The verification link is invalid or has expired.');
        }

        // 2. Find the subscriber using the token
        $subscription = NewsletterSubscription::where('unsubscribe_token', $request->token)->first();

        if (!$subscription) {
            return redirect('/')->with('error', 'The subscription token is invalid.');
        }
        
        // Handle race condition: already verified
        if ($subscription->is_verified) {
            return redirect('/')->with('info', 'You are already subscribed!');
        }

        // 3. Mark as verified
        $subscription->is_verified = true;
        $subscription->save();

        return redirect('/')->with('success', 'Subscription confirmed! Thank you for joining our list.');
    }
}
