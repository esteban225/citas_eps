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
        Schema::create('Userseps', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the user
            $table->string('email')->unique(); // Email of the user
            $table->string('identificationType'); // 'CC', 'TI', 'CE', etc.
            $table->integer('identificationNumber')->unique(); // Unique identification number
            $table->string('phone')->nullable(); // Phone number of the user
            $table->string('address')->nullable(); // Address of the user
            $table->boolean('status')->default(1); // 'active', 'inactive'
            $table->string('role')->default('user'); // 'admin', 'user', etc.
            $table->foreignId('healthcenters_id')->constrained('healthcenters')->onDelete('cascade'); // Health center where the user works
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Userseps');
    }
};
