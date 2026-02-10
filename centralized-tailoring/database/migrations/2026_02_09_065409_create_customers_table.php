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
        Schema::create('customers', function (Blueprint $table) {

        $table->id();
        $table->foreignId('tailoring_shop_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone_number')->nullable(); // Important for "Your suit is ready" SMS
            $table->string('email')->nullable();
            $table->text('address')->nullable(); // For delivery
            
            // This is a "JSON Column". It lets us save measurements like 
            // {"waist": 32, "length": 40} without making 50 different columns.
            // It is a very advanced/impressive feature for a Capstone.
            $table->json('measurements')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
