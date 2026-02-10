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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        
        // 1. LINK TO SHOP (Crucial for filtering)
        $table->foreignId('tailoring_shop_id')->constrained()->cascadeOnDelete();
        
        // 2. LINK TO CUSTOMER
        $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
        
        // 3. LINK TO SERVICE (What are they buying?)
        $table->foreignId('service_id')->constrained()->cascadeOnDelete();

        // 4. ORDER DETAILS
        $table->string('status')->default('Pending'); // Pending, Measuring, Sewing, Ready, Completed
        $table->date('expected_completion_date')->nullable();
        $table->decimal('total_price', 10, 2); // e.g., 500.00
        $table->text('notes')->nullable(); // Special instructions like "Extra loose fit"

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
