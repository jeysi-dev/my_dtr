<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DtrRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'position',
        'official_time',
        'office_department',
        'month_year',
        'record_month',
    ];

    protected $casts = [
        'record_month' => 'date',
    ];

    /**
     * Get the DTR entries for this record.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(DtrEntry::class)->orderBy('day');
    }

    /**
     * Calculate summary statistics for this DTR record.
     */
    public function getSummary(): array
    {
        $entries = $this->entries;

        $daysWorked = 0;
        $absences = 0;
        $holidays = 0;
        $tardiness = 0;
        $undertime = 0;
        $leave = 0;
        $restDay = 0;
        $specialHoliday = 0;
        $legalHoliday = 0;

        foreach ($entries as $entry) {
            // Skip weekends unless specifically worked
            $isWeekend = in_array($entry->day_name, ['Sat', 'Sun']);

            if ($entry->is_holiday || !empty($entry->holiday_type)) {
                if (str_contains(strtolower($entry->holiday_type ?? ''), 'legal')) {
                    $legalHoliday++;
                } elseif (str_contains(strtolower($entry->holiday_type ?? ''), 'special')) {
                    $specialHoliday++;
                } else {
                    $holidays++;
                }
            } elseif ($isWeekend) {
                if ($entry->time_in_am || $entry->time_in_pm) {
                    $daysWorked++;
                }
                // Don't count weekends as absences
            } elseif ($entry->time_in_am || $entry->time_in_pm) {
                $daysWorked++;
            } elseif (!$isWeekend) {
                // Only count as absence if it's a weekday with no time entries
                $absences++;
            }
        }

        return [
            'days_worked' => $daysWorked,
            'absences' => $absences,
            'tardiness' => $tardiness,
            'undertime' => $undertime,
            'overtime' => 0,
            'leave' => $leave,
            'rest_day' => $restDay,
            'ta_free' => 0,
            'ut_free' => 0,
            'special_holiday' => $specialHoliday,
            'legal_holiday' => $legalHoliday,
            'total_days_worked' => $daysWorked,
            'no_lunch' => 0,
        ];
    }
}
