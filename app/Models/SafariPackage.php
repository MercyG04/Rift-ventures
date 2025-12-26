<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PackageType;
use App\Enums\PackageCategory;
use App\Enums\currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SafariPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',         
        'category',     
        'location',
        'duration',
        'starting_price',        
        'currency',     
        'min_travelers',
        'max_travelers',
        'description',
        'itinerary',
        'includes_flight',
        'includes_sgr',
        'includes_hotel',
        'includes_bus_transport',
        'includes_tour_guide',
        'includes_excursions',
        'includes_drinks',
        'other_inclusions', 
        'exclusions',       
        'featured_image_path',
        'is_featured',      
        'is_special_offer', 
        'is_active',
    ];

    protected $casts = [
        'type' => PackageType::class,
        'category' => PackageCategory::class,
        'currency' => Currency::class,
        'is_featured' => 'boolean',
        'is_special_offer' => 'boolean',
        'is_active' => 'boolean',
        'includes_flight' => 'boolean',
        'includes_sgr' => 'boolean',
        'includes_hotel' => 'boolean',
        'includes_bus_transport' => 'boolean',
        'includes_tour_guide' => 'boolean',
        'includes_excursions' => 'boolean',
        'includes_drinks' => 'boolean',
    ];
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    public function variants(): HasMany
    {
        return $this->hasMany(PackageVariant::class);
    }
    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to include only inactive/archived packages.
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('is_active', false);
    }
    // Note: You will add public function images() here later when we do the gallery.

}
