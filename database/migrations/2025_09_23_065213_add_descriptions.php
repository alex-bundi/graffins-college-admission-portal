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
        Schema::table('applicant', function (Blueprint $table) {
            $table->string('country_name')->nullable();
            $table->string('residence_description')->nullable();
            $table->string('marketing_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant', function (Blueprint $table) {
            //
        });
    }
};
