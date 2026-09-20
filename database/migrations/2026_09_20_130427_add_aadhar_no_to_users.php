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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('aadhar_no')->unique()->nullable();
            $table->string('aadhar_image')->nullable();
            $table->integer('pan_no')->unique()->nullable();
            $table->string('pan_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['aadhar_no', 'aadhar_image', 'pan_no', 'pan_image']);
        });
    }
};
