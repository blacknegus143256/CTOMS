<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. CATEGORIES (The "Big Buckets")
        // Examples: "Fabrics", "Services", "Accessories", "Threads"
        Schema::create('attribute_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Fabrics"
            $table->string('slug')->unique(); // e.g., "fabrics" (for coding)
            $table->timestamps();
        });

        // 2. ATTRIBUTES (The "Specific Items" inside the buckets)
        // Examples: "Silk", "Cotton", "YKK Zipper", "Gold Button"
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_category_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Silk"
            $table->timestamps();
        });

        // 3. SHOP ATTRIBUTES (The "Menu" for a specific shop)
        // This is where Ben says: "I have Silk (Item #5) and I sell it for ₱150."
        Schema::create('shop_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tailoring_shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            
            // --- PRICING & DETAILS ---
            // "Price" is the number (150.00)
            $table->decimal('price', 10, 2)->nullable(); 
            
            // "Unit" is the context ("per yard", "per piece", "starting at")
            $table->string('unit')->default('per item'); 
            
            // "Notes" is for variations ("Available in Red, Blue, and Black")
            $table->text('notes')->nullable(); 
            
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_attributes');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_categories');
    }
};