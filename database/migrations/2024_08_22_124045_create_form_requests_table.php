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
        Schema::create('form_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('business_email');
            $table->string('phone_number');
            $table->string('company_name');
            $table->string('job_title');
            $table->string('average_online_orders');
            $table->boolean('has_store');
            $table->json('hear_about'); // To store multiple selections as a JSON array
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_requests');
    }
};
