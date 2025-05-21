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
        Schema::create('Doctors', function (Blueprint $table) {
            $table->id();
            $table->string('identificationType'); // 'CC', 'TI', 'CE', etc.
            $table->integer('identificationNumber')->unique(); // Unique identification number
            $table->string('name'); // Name of the doctor
            $table->string('email')->unique(); // Email of the doctor
            $table->string('specialty'); // 'general', 'pediatrician', etc.
            $table->string('license_number')->unique(); // Medical license number
            $table->string('phone')->nullable(); // Phone number of the doctor
            $table->string('address')->nullable(); // Address of the doctor
            $table->boolean('status')->default(1); // 'active', 'inactive'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Doctors');
    }
};
