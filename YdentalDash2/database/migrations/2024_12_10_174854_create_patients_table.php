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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->String('name');
            $table->String('email')->unique();
            $table->String('password');
            $table->String('confirmPassword');
            $table->String('id_card')->nullable();
            $table->String('gender');
            $table->String('address');
            $table->date('date_of_birth');
            $table->String('phone_number')->unique();
            $table->string('userType');

            $table->string('isBlocked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
