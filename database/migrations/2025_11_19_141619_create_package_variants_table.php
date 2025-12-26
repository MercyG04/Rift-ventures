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
        Schema::create('package_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safari_package_id')->constrained('safari_packages')->cascadeOnDelete();
            $table->string('name'); 
            $table->integer('price');
            $table->text('description')->nullable(); 
            $table->text('inclusions')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_variants');
    }
};
