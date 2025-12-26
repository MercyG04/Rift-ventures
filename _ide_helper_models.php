<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $safari_package_id
 * @property string|null $package_variant_name
 * @property \Illuminate\Support\Carbon $booking_date
 * @property int $num_travelers
 * @property int $total_price
 * @property \App\Enums\BookingStatus $status
 * @property string|null $special_requests
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\TravelerDetail|null $primaryContact
 * @property-read \App\Models\SafariPackage $safariPackage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TravelerDetail> $travelerDetails
 * @property-read int|null $traveler_details_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereBookingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereNumTravelers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking wherePackageVariantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereSafariPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereSpecialRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Booking whereUserId($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $unsubscribe_token
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereUnsubscribeToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscription whereUpdatedAt($value)
 */
	class NewsletterSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $safari_package_id
 * @property string $name
 * @property int $price
 * @property string|null $description
 * @property string|null $inclusions
 * @property string|null $featured_image_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SafariPackage $safariPackage
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereFeaturedImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereInclusions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereSafariPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageVariant whereUpdatedAt($value)
 */
	class PackageVariant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property int $amount
 * @property string $currency
 * @property \App\Enums\PaymentStatus $status
 * @property string|null $transaction_id
 * @property string|null $idempotency_key
 * @property string|null $provider
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereIdempotencyKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property \App\Enums\PackageType $type
 * @property \App\Enums\PackageCategory $category
 * @property int $min_travelers
 * @property int|null $max_travelers
 * @property string $location
 * @property string $duration
 * @property int $starting_price
 * @property string $currency
 * @property string|null $description
 * @property string|null $itinerary
 * @property bool $includes_flight
 * @property bool $includes_sgr
 * @property bool $includes_bus_transport
 * @property bool $includes_hotel
 * @property bool $includes_tour_guide
 * @property bool $includes_excursions
 * @property bool $includes_drinks
 * @property string|null $other_inclusions
 * @property string|null $exclusions
 * @property string|null $featured_image_path
 * @property bool $is_featured
 * @property bool $is_special_offer
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PackageVariant> $variants
 * @property-read int|null $variants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $wishlistedBy
 * @property-read int|null $wishlisted_by_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereExclusions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereFeaturedImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesBusTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesDrinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesExcursions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesFlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesHotel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesSgr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIncludesTourGuide($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereIsSpecialOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereItinerary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereMaxTravelers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereMinTravelers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereOtherInclusions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereStartingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SafariPackage whereUpdatedAt($value)
 */
	class SafariPackage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $author_name
 * @property string $content
 * @property int $rating
 * @property bool $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUserId($value)
 */
	class Testimonial extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $booking_id
 * @property bool $is_primary_contact
 * @property string $full_name
 * @property string|null $email
 * @property string|null $passport_number
 * @property string|null $id_number
 * @property string|null $date_of_birth
 * @property string|null $passport_expiry
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereIsPrimaryContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail wherePassportExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail wherePassportNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TravelerDetail whereUpdatedAt($value)
 */
	class TravelerDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \App\Enums\UserRole $role
 * @property string|null $phone_number
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Testimonial> $testimonials
 * @property-read int|null $testimonials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SafariPackage> $wishlist
 * @property-read int|null $wishlist_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

