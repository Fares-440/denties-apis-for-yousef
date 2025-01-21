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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->String('student_image')->nullable();
            $table->String('name');
            $table->String('email')->unique();
            $table->String('password');
            $table->String('confirmPassword');
            $table->String('gender');
            $table->String('level');
            $table->String('phone_number')->unique();
            $table->String('university_card_number');
            $table->String('university_card_image');
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
        Schema::dropIfExists('students');
    }
};
