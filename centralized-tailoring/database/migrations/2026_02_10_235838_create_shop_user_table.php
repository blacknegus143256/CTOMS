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
    Schema::create('shop_user', function (Blueprint $table) {
        $table->id();
        // Link to the User
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        
        // Link to the Shop (Note: We use the exact table name 'tailoring_shops' here for clarity)
        $table->foreignId('tailoring_shop_id')->constrained('tailoring_shops')->cascadeOnDelete();
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_user');
    }
};
