<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PackageVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'safari_package_id',
        'name',        
        'price',       
        'description', 
        'inclusions',
        'featured_image_path',
        
    ];

    
    public function safariPackage(): BelongsTo
    {
        return $this->belongsTo(SafariPackage::class);
    }
}
