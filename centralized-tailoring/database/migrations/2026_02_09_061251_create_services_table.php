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
    Schema::create('services', function (Blueprint $table) {
        $table->id();
        // This links the service to a specific shop (Important for multi-shop systems!)
        $table->foreignId('tailoring_shop_id')->constrained()->cascadeOnDelete();
        
        $table->string('service_name'); // e.g. "Hemming"
        $table->decimal('price', 10, 2); // e.g. 150.00
        $table->string('duration_days')->nullable(); // e.g. "3 days"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
