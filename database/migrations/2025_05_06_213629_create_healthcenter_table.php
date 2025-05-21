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
        Schema::create('Healthcenters', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the health center
            $table->string('address'); // Address of the health center
            $table->string('phone')->nullable(); // Phone number of the health center
            $table->string('email')->unique(); // Email of the health center
            $table->string('type'); // Type of health center (e.g., 'hospital', 'clinic', etc.)
            $table->boolean('status')->default(1); // Status of the health center (e.g., 'active', 'inactive')

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Healthcenters');
    }
};
