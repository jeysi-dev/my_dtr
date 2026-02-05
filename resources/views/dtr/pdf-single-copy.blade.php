<!-- Header with Logo -->
<table style="width: 100%; margin-bottom: 3px;">
    <tr>
        <td style="width: 45px; vertical-align: top; text-align: center;">
            <img src="{{ public_path('assets/img/opapru_logo.png') }}" alt="OPAPRU" style="width: 40px; height: 40px;">
        </td>
        <td style="text-align: center; vertical-align: top;">
            <div class="header">
                <h1>DAILY TIME RECORD</h1>
                <h2>Office of the Presidential Adviser on<br>Peace, Reconciliation and Unity</h2>
            </div>
        </td>
        <td style="width: 45px;"></td>
    </tr>
</table>

<!-- Month/Year Row -->
<div class="month-row">
    <span>For the month of</span>
    <span class="month-value">{{ $record->month_year }}</span>
    <span class="fy-indicator">(FY)</span>
</div>

<!-- Employee Information -->
<table class="info-table">
    <tr>
        <td class="label">Employee Name:</td>
        <td class="value">{{ $record->employee_name }}</td>
    </tr>
    <tr>
        <td class="label">Position:</td>
        <td class="value">{{ $record->position }}</td>
    </tr>
    <tr>
        <td class="label">Official Time:</td>
        <td class="value">{{ $record->official_time ?? 'FLEXITIME' }}</td>
    </tr>
    <tr>
        <td class="label">Office/Department:</td>
        <td class="value">{{ $record->office_department }}</td>
    </tr>
</table>

<!-- DTR Table -->
<table class="dtr-table">
    <thead>
        <tr>
            <th rowspan="2" class="day-col">Days</th>
            <th colspan="2">AM</th>
            <th colspan="2">PM</th>
            <th rowspan="2" class="hours-col">A/U/T<br>Hours|Mins</th>
            <th rowspan="2" class="remark-col">Remark<br>Holiday</th>
        </tr>
        <tr>
            <th class="time-col">In</th>
            <th class="time-col">Out</th>
            <th class="time-col">In</th>
            <th class="time-col">Out</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($record->entries as $entry)
            @php
                $isWeekend = in_array($entry->day_name, ['Sat', 'Sun']);
                $hasHoliday = !empty($entry->holiday_type);
                $isFlexiplace = $hasHoliday && strtoupper($entry->holiday_type) === 'FLEXIPLACE';
            @endphp
            <tr class="{{ $isWeekend ? 'weekend-row' : '' }} {{ $isFlexiplace ? 'flexiplace-row' : '' }}">
                <td class="day-col">
                    {{ str_pad($entry->day, 2, '0', STR_PAD_LEFT) }}. {{ $entry->day_name }}
                </td>
                @if ($hasHoliday)
                    <td colspan="4" class="holiday-text">{{ $entry->holiday_type }}</td>
                @else
                    <td class="time-col">{{ $entry->formatted_time_in_am ?? '' }}</td>
                    <td class="time-col">{{ $entry->formatted_time_out_am ?? '' }}</td>
                    <td class="time-col">{{ $entry->formatted_time_in_pm ?? '' }}</td>
                    <td class="time-col">{{ $entry->formatted_time_out_pm ?? '' }}</td>
                @endif
                <td class="hours-col"></td>
                <td class="remark-col"></td>
            </tr>
        @endforeach
        <!-- Summary Section - Integrated -->
        <tr>
            <td colspan="7" class="summary-title">TOTAL SUMMARY</td>
        </tr>
        <tr class="summary-row">
            <td class="summary-label">Days Worked</td>
            <td class="summary-value">0</td>
            <td class="summary-label">Tardiness</td>
            <td class="summary-value">0</td>
            <td class="summary-label">Leave</td>
            <td class="summary-value">0</td>
            <td></td>
        </tr>
        <tr class="summary-row">
            <td class="summary-label">Absences</td>
            <td class="summary-value">0</td>
            <td class="summary-label">TA Free</td>
            <td class="summary-value">0</td>
            <td class="summary-label">Rest Day</td>
            <td class="summary-value">0</td>
            <td></td>
        </tr>
        <tr class="summary-row">
            <td class="summary-label">Overtime</td>
            <td class="summary-value">0</td>
            <td class="summary-label">Undertime</td>
            <td class="summary-value">0</td>
            <td class="summary-label">Special Hol.</td>
            <td class="summary-value">0</td>
            <td></td>
        </tr>
        <tr class="summary-row">
            <td class="summary-label">Total Days Worked</td>
            <td class="summary-value">0</td>
            <td class="summary-label">UT Free</td>
            <td class="summary-value">0</td>
            <td class="summary-label">Legal Hol.</td>
            <td class="summary-value">0</td>
            <td></td>
        </tr>
    </tbody>
</table>

<!-- Certification -->
<p class="certification">
    I CERTIFY on my honor that the above is a true and correct report of the hours of work
    performed, record of which was made daily at the time of arrival and departure from office.
</p>

<!-- Employee Signature -->
<div class="signature-section">
    <p class="signature-name">{{ $record->employee_name }}</p>
    <p class="signature-position">{{ $record->position }} / {{ $record->office_department }}</p>
</div>

<!-- Director Signature -->
<div class="director-section">
    <p class="director-name">DIR. LEILANNIE T. DISOMANGCOP</p>
    <p class="director-title">Director III, GASS</p>
</div>

<!-- Remarks -->
<div class="remarks-section">
    <span class="remarks-label">REMARKS:</span>
</div>
