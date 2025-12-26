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
        Schema::create('travel_services', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('service_type')->index(); 
            $table->string('destination')->nullable();
            $table->date('travel_date')->nullable();
            $table->string('duration')->nullable();
            $table->integer('no_of_travellers')->default(1);
            $table->json('additional_details')->nullable(); 
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable(); 
           
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_services');
    }
};
