<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('traveler_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();   
            $table->foreignId('travel_service_id')->nullable()->constrained('travel_services')->cascadeOnDelete();       
            $table->boolean('is_primary_contact')->default(false);
            $table->string('full_name');
            $table->string('email')->nullable(); 
            $table->text('passport_number')->nullable(); 
            $table->text('id_number')->nullable();
            $table->text('date_of_birth')->nullable();
            $table->text('passport_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traveler_details');
    }
};
