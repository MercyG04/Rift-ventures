<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PackageCategory;
use App\Enums\PackageType;
use App\Enums\Currency;



return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('safari_packages', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique(); 

        $table->string('type')->default(PackageType::LOCAL->value)->index();
        $table->string('category')->default(PackageCategory::SAFARI->value)->index();
        $table->integer('min_travelers')->default(1);
        $table->integer('max_travelers')->nullable();

        
        $table->string('location')->index(); 
        $table->string('duration');
        $table->integer('starting_price'); 
        $table->string('currency')->default(CURRENCY::KES->value);   

        
        
        $table->text('description')->nullable();
        $table->text('itinerary')->nullable();
        
        
        $table->boolean('includes_flight')->default(false);
        $table->boolean('includes_sgr')->default(false);
        $table->boolean('includes_bus_transport')->default(false);
        $table->boolean('includes_hotel')->default(false);
        $table->boolean('includes_tour_guide')->default(false);
        $table->boolean('includes_excursions')->default(false);
        $table->boolean('includes_drinks')->default(false);
       
        $table->text('other_inclusions')->nullable();
        $table->text('exclusions')->nullable();
        
        // --- Site Management ---
        $table->string('featured_image_path')->nullable();
        
        $table->boolean('is_featured')->default(false)->index();
        $table->boolean('is_special_offer')->default(false)->index(); 
        $table->boolean('is_active')->default(true);

        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_packages');
    }
};
