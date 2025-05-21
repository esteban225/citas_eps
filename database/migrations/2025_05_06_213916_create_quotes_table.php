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
        Schema::create('Quotes', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'general', 'specialist', etc.
            $table->date('date');
            $table->string('status')->default('pending'); // 'pending', 'confirmed', 'canceled'
            $table->string('reason')->nullable(); // Reason for the appointment
            $table->string('observations')->nullable(); // Observations made by the doctor
            $table->foreignId('doctors_id')->constrained('doctors')->onDelete('cascade'); // Doctor assigned to the appointment
            $table->foreignId('userseps_id')->constrained('userseps')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Quotes');
    }
};
