<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\CustPackageController;
use App\Http\Controllers\CustBookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustTravelerDetailsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TravelServiceController;

use App\Http\Controllers\Admin\AdmPackageController;
use App\Http\Controllers\Admin\AdmPackageVariantController;
use App\Http\Controllers\Admin\AdmBookingController;
use App\Http\Controllers\Admin\AdmTravelerDetailsController;
use App\Http\Controllers\Admin\AdmTestimonialController;
use App\Http\Controllers\Admin\AdmTravelServiceController;

Route::get('/', [HomeController::class, 'index'])->name('home');
//customer/packages/show.blade.php
Route::get('/local-tours', [CustPackageController::class, 'local'])->name('local.index');
Route::get('/international-tours', [CustPackageController::class, 'international'])->name('international.index');
Route::get('/safaris', [CustPackageController::class, 'safaris'])->name('safaris.index');
Route::get('/package/{slug}', [CustPackageController::class, 'show'])->name('package.show');

Route::get('/offers', [CustPackageController::class, 'offers'])->name('packages.offers');

// 3. SEARCH
Route::get('/search', [CustPackageController::class, 'search'])->name('packages.search'); // Full Search Page
Route::get('/api/search/live', [CustPackageController::class, 'searchLive'])->name('api.search.live'); // JSON for Alpine Dropdown

// Route for specific variant details page
Route::get('/packages/{package}/variants/{variant}', [CustPackageController::class, 'showVariant'])->name('customer.packages.variantdetails');

// 4. BOOKING ENTRY POINT
// Customers can see the form without being logged in (redirects if guest)
Route::get('/safaris/{package}/book', [CustBookingController::class, 'create'])->name('bookings.create');
Route::post('/safaris/{package}/book', [CustBookingController::class, 'store'])->name('bookings.store');

Route::get('/plan-your-trip/resume', [TravelServiceController::class, 'resume'])->name('services.resume');
// 2. Main Form
Route::get('/plan-your-trip', [TravelServiceController::class, 'index'])->name('services.index');
// 3. Submission
Route::post('/plan-your-trip', [TravelServiceController::class, 'store'])
    ->middleware('throttle:5,1') 
    ->name('services.store');
// 5. NEWSLETTER & PUBLIC TESTIMONIALS
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index'); // View all approved

// 1. NEWSLETTER VERIFICATION
Route::get('/newsletter/verify', [NewsletterController::class, 'verify'])
    ->middleware('signed')
    ->name('newsletter.verify');

// 2. SECURE TRAVELER DETAILS FORM

Route::middleware('signed')->group(function () {
    // For Standard Bookings
    Route::get('/bookings/{booking}/travelers', [CustTravelerDetailsController::class, 'edit'])
        ->name('bookings.travelers.edit');
    Route::post('/bookings/{booking}/travelers', [CustTravelerDetailsController::class, 'update'])
    ->name('bookings.travelers.update');    
        
    // For Custom Service Requests
    Route::get('/services/{service}/travelers', [CustTravelerDetailsController::class, 'editService'])
        ->name('services.travelers.edit');
});

// Traveler Details Submission (Public POST, protected by logic/session inside)
Route::post('/bookings/{booking}/travelers', [CustTravelerDetailsController::class, 'update'])->name('bookings.travelers.update');
Route::post('/services/{service}/travelers', [CustTravelerDetailsController::class, 'updateService'])->name('services.travelers.update');



Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        
        if (session()->has('pending_service_request')) {
            
            return redirect()->route('services.resume');
        }

        
        return redirect()->route('profile.dashboard');
    })->name('dashboard');

    Route::get('/my-account', [ProfileController::class, 'edit'])->name('profile.dashboard'); 
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     // --- BOOKING MANAGEMENT ---
    Route::get('/bookings/resume', [CustBookingController::class, 'resume'])->name('bookings.resume'); // The Guest->User Bridge
    Route::get('/bookings/{booking}/checkout', [CustBookingController::class, 'checkout'])->name('bookings.checkout');
    Route::post('/bookings/{booking}/confirm', [CustBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('/my-bookings', [CustBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [CustBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [CustBookingController::class, 'cancel'])->name('bookings.cancel');

    // --- PAYMENTS ---
    Route::get('/bookings/{booking}/pay', [PaymentController::class, 'showPaymentForm'])->name('payment.create');
    Route::post('/bookings/{booking}/payment/confirm', [PaymentController::class, 'handlePaymentSuccess'])->name('payment.success');

    // --- WISHLIST ---
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{package}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // --- TESTIMONIAL SUBMISSION ---
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
});
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // 1. DASHBOARD
    
    Route::get('/dashboard', [AdmPackageController::class, 'index'])->name('packages.index');

    Route::patch('packages/{package}/toggle-status', [AdmPackageController::class, 'toggleStatus', ])
        ->name('packages.toggle-status');
    Route::resource('packages', AdmPackageController::class)->except(['show','destroy']);
    Route::resource('packages.variants', AdmPackageVariantController::class)->except(['index', 'show']);

    // 3. BOOKING OVERSIGHT
    Route::get('/bookings', [AdmBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdmBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdmBookingController::class, 'updateStatus'])->name('bookings.update-status');
    // Email Triggers
    Route::post('/bookings/{booking}/resend-confirmation', [AdmBookingController::class, 'resendConfirmation'])->name('bookings.resend-confirmation');
    Route::post('/bookings/{booking}/resend-cancellation', [AdmBookingController::class, 'resendCancellation'])->name('bookings.resend-cancellation');
    Route::post('/bookings/{booking}/travelers/resend', [AdmTravelerDetailsController::class, 'resendRequest'])->name('travelers.resend');

    // 4. TRAVELER PII MANAGEMENT (Encrypted/Decrypted View)
    

    // 5. TESTIMONIAL MODERATION
    Route::get('/testimonials', [AdmTestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [AdmTestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [AdmTestimonialController::class, 'store'])->name('testimonials.store');
    Route::patch('/testimonials/{testimonial}/reply', [AdmTestimonialController::class, 'reply'])->name('testimonials.reply');
    Route::post('/testimonials/{testimonial}/approve', [AdmTestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::delete('/testimonials/{testimonial}', [AdmTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::resource('services', AdmTravelServiceController::class)
    ->except(['create', 'store', 'edit']);
});

require __DIR__.'/auth.php';

