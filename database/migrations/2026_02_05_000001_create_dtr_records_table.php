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
        Schema::create('dtr_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('position');
            $table->string('official_time')->nullable()->default('FLEXITIME');
            $table->string('office_department')->default('GASS - ICTD');
            $table->string('month_year'); // Format: "JANUARY 1-31, 2026"
            $table->date('record_month'); // Actual date for querying (first day of month)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtr_records');
    }
};
