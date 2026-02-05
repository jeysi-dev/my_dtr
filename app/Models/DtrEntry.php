<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DtrEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'dtr_record_id',
        'day',
        'date',
        'day_name',
        'time_in_am',
        'time_out_am',
        'time_in_pm',
        'time_out_pm',
        'hours_mins',
        'is_holiday',
        'holiday_type',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'day' => 'integer',
        'is_holiday' => 'boolean',
    ];

    /**
     * Get the DTR record that owns this entry.
     */
    public function dtrRecord(): BelongsTo
    {
        return $this->belongsTo(DtrRecord::class);
    }

    /**
     * Calculate total work hours for this entry.
     */
    public function calculateHours(): ?string
    {
        $totalMinutes = 0;

        // Calculate AM session
        if ($this->time_in_am && $this->time_out_am) {
            $inAm = Carbon::parse($this->time_in_am);
            $outAm = Carbon::parse($this->time_out_am);
            $totalMinutes += $inAm->diffInMinutes($outAm);
        }

        // Calculate PM session
        if ($this->time_in_pm && $this->time_out_pm) {
            $inPm = Carbon::parse($this->time_in_pm);
            $outPm = Carbon::parse($this->time_out_pm);
            $totalMinutes += $inPm->diffInMinutes($outPm);
        }

        if ($totalMinutes <= 0) {
            return null;
        }

        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;

        return sprintf('%d:%02d', $hours, $mins);
    }

    /**
     * Format time for display (e.g., "7:38 am")
     */
    public static function formatTimeForDisplay(?string $time): ?string
    {
        if (!$time) {
            return null;
        }

        return Carbon::parse($time)->format('g:i a');
    }

    /**
     * Get formatted AM time in
     */
    public function getFormattedTimeInAmAttribute(): ?string
    {
        return self::formatTimeForDisplay($this->time_in_am);
    }

    /**
     * Get formatted AM time out
     */
    public function getFormattedTimeOutAmAttribute(): ?string
    {
        return self::formatTimeForDisplay($this->time_out_am);
    }

    /**
     * Get formatted PM time in
     */
    public function getFormattedTimeInPmAttribute(): ?string
    {
        return self::formatTimeForDisplay($this->time_in_pm);
    }

    /**
     * Get formatted PM time out
     */
    public function getFormattedTimeOutPmAttribute(): ?string
    {
        return self::formatTimeForDisplay($this->time_out_pm);
    }
}
