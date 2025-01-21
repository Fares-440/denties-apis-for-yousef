<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('thecases', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->after('id'); // Add nullable student_id
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null'); // Foreign key constraint
        });
    }

    public function down()
    {
        Schema::table('thecases', function (Blueprint $table) {
            $table->dropForeign(['student_id']); // Drop foreign key constraint
            $table->dropColumn('student_id'); // Drop the column
        });
    }
};
