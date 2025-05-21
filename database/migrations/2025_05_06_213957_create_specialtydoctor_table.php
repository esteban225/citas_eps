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
        Schema::create('Specialtydoctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctors_id')->constrained('doctors')->onDelete('cascade'); // Doctor assigned to the specialty
            $table->string('specialty'); // specialty assigned to the doctor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Specialtydoctors');
    }
};
