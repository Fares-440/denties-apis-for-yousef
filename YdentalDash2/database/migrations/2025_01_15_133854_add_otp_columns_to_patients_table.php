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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('otp')->nullable()->after('password'); // Store the OTP
            $table->timestamp('otp_expires_at')->nullable()->after('otp'); // Store the OTP expiry time
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('otp');
            $table->dropColumn('otp_expires_at');
        });
    }
};
