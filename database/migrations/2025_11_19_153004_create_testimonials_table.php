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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();         
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // If user_id is null (manual entry by admin), we use this name
            $table->string('author_name');
            $table->text('admin_response')->nullable();
            
            $table->text('content');
            $table->integer('rating')->default(5); // 1 to 5 stars
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
