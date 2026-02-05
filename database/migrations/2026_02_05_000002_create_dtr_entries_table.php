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
        Schema::create('dtr_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dtr_record_id')->constrained('dtr_records')->onDelete('cascade');
            $table->integer('day'); // Day number (1-31)
            $table->date('date');
            $table->string('day_name', 10); // Mon, Tue, Wed, Thu, Fri, Sat, Sun
            $table->time('time_in_am')->nullable();
            $table->time('time_out_am')->nullable();
            $table->time('time_in_pm')->nullable();
            $table->time('time_out_pm')->nullable();
            $table->string('hours_mins')->nullable(); // Calculated work hours as string (e.g., "5:07")
            $table->boolean('is_holiday')->default(false);
            $table->string('holiday_type')->nullable(); // "Holiday, New Year's Day", "Work Suspension", "FLEXIPLACE", etc.
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['dtr_record_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtr_entries');
    }
};
