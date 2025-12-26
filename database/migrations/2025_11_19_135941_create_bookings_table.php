<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BookingStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('safari_package_id')->constrained('safari_packages')->cascadeOnDelete();
            $table->string('package_variant_name')->nullable(); 
            $table->date('booking_date');
            $table->string('contact_name');  
            $table->string('contact_email'); 
            $table->string('contact_phone'); 
            $table->integer('num_travelers');
            $table->integer('total_price');
            $table->string('status')->default(BookingStatus::PENDING->value);
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
