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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('cart_token')->nullable(); // Nullable for unauthenticated users
            $table->foreignId('service_id')->constrained('our_services')->onDelete('cascade');
            $table->timestamps();

            // Add unique index for cart_token and service_id combination
            $table->unique(['cart_token', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
