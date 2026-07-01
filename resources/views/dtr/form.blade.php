@extends('layouts.app')

@section('content')
    <div x-data="dtrApp()" x-init="init()" x-cloak>
        <!-- Header -->
        <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-6">
                <img src="{{ asset('assets/img/opapru_logo.png') }}" alt="OPAPRU Logo" class="w-20 h-20">
                <div class="flex-1 text-center">
                    <h1 class="text-2xl font-bold text-gray-800">DAILY TIME RECORD</h1>
                    <p class="text-gray-600">Office of the Presidential Adviser on Peace, Reconciliation and Unity</p>
                </div>
                <div class="w-20 h-20"></div>
            </div>

            <!-- Employee Information -->
            <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Employee Name:</label>
                    <input type="text" x-model="form.employee_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter employee name">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Position:</label>
                    <input type="text" x-model="form.position"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter position">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Official Time:</label>
                    <input type="text" x-model="form.official_time"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="FLEXITIME">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Office/Department:</label>
                    <input type="text" x-model="form.office_department"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="GASS - ICTD">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Month/Year:</label>
                    <input type="month" x-model="selectedMonth" @change="generateEntries()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- DTR Tables - Two Copies Side by Side -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <!-- First DTR Copy -->
            <div class="p-4 overflow-x-auto bg-white rounded-lg shadow-md">
                <h3 class="mb-3 text-sm font-semibold text-center">Copy 1</h3>
                <table class="w-full border-collapse dtr-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="w-16">Days</th>
                            <th colspan="2" class="text-center">AM</th>
                            <th colspan="2" class="text-center">PM</th>
                            <th rowspan="2" class="w-20">A/U/T<br>Hours|Mins</th>
                            <th rowspan="2" class="w-48">Holiday/Leave Type</th>
                            <th rowspan="2" class="w-32">Remark</th>
                        </tr>
                        <tr>
                            <th class="w-24">In</th>
                            <th class="w-24">Out</th>
                            <th class="w-24">In</th>
                            <th class="w-24">Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(entry, index) in entries" :key="entry.day">
                            <tr
                                :class="{
                                    'weekend-row': entry.day_name === 'Sat' || entry.day_name === 'Sun',
                                    'holiday-row': entry.is_holiday || entry.holiday_type
                                }">
                                <td class="font-medium text-center">
                                    <span x-text="String(entry.day).padStart(2, '0')"></span>.
                                    <span x-text="entry.day_name"></span>
                                </td>
                                <template x-if="entry.holiday_type && entry.holiday_type !== 'FLEXIPLACE'">
                                    <td colspan="4" class="italic text-center text-gray-700">
                                        <span x-text="entry.holiday_type"></span>
                                    </td>
                                </template>
                                <template
                                    x-if="(entry.day_name === 'Sat' || entry.day_name === 'Sun') && (!entry.holiday_type || entry.holiday_type === 'FLEXIPLACE')">
                                    <td colspan="4" class="italic text-center text-gray-700">
                                        <span x-text="entry.day_name === 'Sat' ? 'SATURDAY' : 'SUNDAY'"></span>
                                    </td>
                                </template>
                                <template
                                    x-if="(!entry.holiday_type || entry.holiday_type === 'FLEXIPLACE') && !(entry.day_name === 'Sat' || entry.day_name === 'Sun')">
                                    <td>
                                        <input type="time" x-model="entry.time_in_am" @change="calculateHours(index)"
                                            @keydown.tab="handleTabFromAmIn($event, index)"
                                            @keydown.enter="handleTabFromAmIn($event, index)" data-field="time_in_am"
                                            :disabled="entry.day_name === 'Sat' || entry.day_name === 'Sun'"
                                            class="w-full text-center bg-transparent border-0 focus:ring-0">
                                    </td>
                                </template>
                                <template
                                    x-if="(!entry.holiday_type || entry.holiday_type === 'FLEXIPLACE') && !(entry.day_name === 'Sat' || entry.day_name === 'Sun')">
                                    <td>
                                        <input type="time" x-model="entry.time_out_am" @change="calculateHours(index)"
                                            data-field="time_out_am"
                                            :disabled="entry.day_name === 'Sat' || entry.day_name === 'Sun'"
                                            class="w-full text-center bg-transparent border-0 focus:ring-0">
                                    </td>
                                </template>
                                <template
                                    x-if="(!entry.holiday_type || entry.holiday_type === 'FLEXIPLACE') && !(entry.day_name === 'Sat' || entry.day_name === 'Sun')">
                                    <td>
                                        <input type="time" x-model="entry.time_in_pm" @change="calculateHours(index)"
                                            data-field="time_in_pm"
                                            :disabled="entry.day_name === 'Sat' || entry.day_name === 'Sun'"
                                            class="w-full text-center bg-transparent border-0 focus:ring-0">
                                    </td>
                                </template>
                                <template
                                    x-if="(!entry.holiday_type || entry.holiday_type === 'FLEXIPLACE') && !(entry.day_name === 'Sat' || entry.day_name === 'Sun')">
                                    <td>
                                        <input type="time" x-model="entry.time_out_pm" @change="calculateHours(index)"
                                            data-field="time_out_pm"
                                            :disabled="entry.day_name === 'Sat' || entry.day_name === 'Sun'"
                                            class="w-full text-center bg-transparent border-0 focus:ring-0">
                                    </td>
                                </template>
                                <td class="text-center">
                                    <span x-text="entry.hours_mins || ''"></span>
                                </td>
                                <td>
                                    <input type="text" x-model="entry.holiday_type"
                                        :disabled="entry.day_name === 'Sat' || entry.day_name === 'Sun'"
                                        placeholder="e.g., Holiday, FLEXIPLACE"
                                        class="w-full text-sm bg-transparent border-0 focus:ring-0">
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <input type="text" x-model="entry.remarks"
                                            :disabled="entry.day_name === 'Sat' || entry.day_name === 'Sun'"
                                            class="w-full text-sm bg-transparent border-0 focus:ring-0">
                                        <button type="button" @click="clearDayTimes(index)"
                                            x-show="(entry.time_in_am || entry.time_out_am || entry.time_in_pm || entry.time_out_pm) && !(entry.day_name === 'Sat' || entry.day_name === 'Sun')"
                                            class="text-xs font-medium text-red-600 hover:text-red-700 whitespace-nowrap">
                                            Clear
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
            <h3 class="pb-2 mb-4 text-lg font-semibold text-center border-b">TOTAL SUMMARY</h3>
            <div class="grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
                <div class="flex justify-between pb-1 border-b">
                    <span>Days Worked:</span>
                    <span x-text="summary.days_worked" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Tardiness:</span>
                    <span x-text="summary.tardiness" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Leave:</span>
                    <span x-text="summary.leave" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Absences:</span>
                    <span x-text="summary.absences" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>TA Free:</span>
                    <span x-text="summary.ta_free" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Rest Day:</span>
                    <span x-text="summary.rest_day" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Overtime:</span>
                    <span x-text="summary.overtime" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Undertime:</span>
                    <span x-text="summary.undertime" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Special Hol.:</span>
                    <span x-text="summary.special_holiday" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Total Days Worked:</span>
                    <span x-text="summary.total_days_worked" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>UT Free:</span>
                    <span x-text="summary.ut_free" class="font-medium">0</span>
                </div>
                <div class="flex justify-between pb-1 border-b">
                    <span>Legal Hol.:</span>
                    <span x-text="summary.legal_holiday" class="font-medium">0</span>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
            <p class="mb-4 text-xs text-gray-600">
                I CERTIFY on my honor that the above is a true and correct report of the hours of work
                performed, record of which was made daily at the time of arrival and departure from office.
            </p>
            <div class="mb-4 text-center">
                <p class="font-semibold" x-text="form.employee_name || '_________________'"></p>
                <p class="text-sm text-gray-600" x-text="form.position || '_________________'"></p>
            </div>
            <div class="pt-4 text-center border-t">
                <p class="font-semibold text-blue-800">DIR. LEILANNIE T. DISOMANGCOP</p>
                <p class="text-sm text-gray-600">Director III, GASS</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap justify-center gap-4">
            <button @click="saveDtr()" :disabled="saving"
                class="px-6 py-3 font-medium text-white transition bg-blue-600 rounded-lg hover:bg-blue-700 disabled:bg-blue-400">
                <span x-show="!saving">💾 Save DTR</span>
                <span x-show="saving">Saving...</span>
            </button>
            <button @click="exportPdf()" :disabled="!recordId || exporting"
                class="px-6 py-3 font-medium text-white transition bg-green-600 rounded-lg hover:bg-green-700 disabled:bg-gray-400">
                <span x-show="!exporting">📄 Export PDF</span>
                <span x-show="exporting">Generating...</span>
            </button>
            <button @click="clearForm()"
                class="px-6 py-3 font-medium text-white transition bg-gray-600 rounded-lg hover:bg-gray-700">
                🗑️ Clear Form
            </button>
            <button @click="deleteRecord()" x-show="recordId"
                class="px-6 py-3 font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700">
                ❌ Delete Record
            </button>
        </div>

        <!-- Messages -->
        <div x-show="message"
            :class="messageType === 'success' ? 'bg-green-100 border-green-500 text-green-700' :
                'bg-red-100 border-red-500 text-red-700'"
            class="fixed max-w-md p-4 border rounded-lg shadow-lg bottom-4 right-4" x-transition>
            <p x-text="message"></p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function dtrApp() {
            return {
                form: {
                    employee_name: '',
                    position: '',
                    official_time: 'FLEXITIME',
                    office_department: 'GASS - ICTD',
                },
                selectedMonth: '',
                entries: [],
                summary: {
                    days_worked: 0,
                    absences: 0,
                    tardiness: 0,
                    undertime: 0,
                    overtime: 0,
                    leave: 0,
                    rest_day: 0,
                    ta_free: 0,
                    ut_free: 0,
                    special_holiday: 0,
                    legal_holiday: 0,
                    total_days_worked: 0,
                },
                recordId: null,
                saving: false,
                exporting: false,
                showSecondCopy: false,
                message: '',
                messageType: 'success',

                init() {
                    // Set default month to current month using local date values
                    const now = new Date();
                    this.selectedMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

                    // Load existing record if provided
                    @if ($selectedRecord)
                        this.loadExistingRecord(@json($selectedRecord));
                    @else
                        this.generateEntries();
                    @endif
                },

                loadExistingRecord(record) {
                    this.recordId = record.id;
                    this.form.employee_name = record.employee_name;
                    this.form.position = record.position;
                    this.form.official_time = record.official_time || 'FLEXITIME';
                    this.form.office_department = record.office_department;

                    // Parse the record_month to set selectedMonth (use local year/month to avoid timezone shifts)
                    const recordMonth = new Date(record.record_month);
                    const recordYear = recordMonth.getFullYear();
                    const recordMonthNum = String(recordMonth.getMonth() + 1).padStart(2, '0');
                    this.selectedMonth = `${recordYear}-${recordMonthNum}`;

                    // Load entries
                    this.entries = record.entries.map(entry => ({
                        day: entry.day,
                        date: entry.date,
                        day_name: entry.day_name,
                        time_in_am: entry.time_in_am ? entry.time_in_am.slice(0, 5) : '',
                        time_out_am: entry.time_out_am ? entry.time_out_am.slice(0, 5) : '',
                        time_in_pm: entry.time_in_pm ? entry.time_in_pm.slice(0, 5) : '',
                        time_out_pm: entry.time_out_pm ? entry.time_out_pm.slice(0, 5) : '',
                        hours_mins: entry.hours_mins,
                        is_holiday: entry.is_holiday,
                        holiday_type: entry.holiday_type,
                        remarks: entry.remarks,
                    }));

                    this.ensureWeekendBreaksBlank();
                    this.calculateSummary();
                },

                generateEntries() {
                    if (!this.selectedMonth) return;

                    const [year, month] = this.selectedMonth.split('-').map(Number);
                    const daysInMonth = new Date(year, month, 0).getDate();
                    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const holidayMap = this.getHolidayMap(year);

                    this.entries = [];
                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month - 1, day);
                        const isoDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                        const isWeekend = dayNames[date.getDay()] === 'Sat' || dayNames[date.getDay()] === 'Sun';
                        const holidayType = holidayMap[isoDate] || '';

                        this.entries.push({
                            day: day,
                            date: isoDate,
                            day_name: dayNames[date.getDay()],
                            time_in_am: '',
                            time_out_am: isWeekend ? '' : '12:00',
                            time_in_pm: isWeekend ? '' : '12:01',
                            time_out_pm: '',
                            hours_mins: '',
                            is_holiday: !!holidayType,
                            holiday_type: holidayType,
                            remarks: '',
                        });
                    }

                    this.recordId = null;
                    this.calculateSummary();
                },

                getHolidayMap(year) {
                    const holidayMapByYear = {
                        2026: {
                            '2026-01-01': "New Year's Day",
                            '2026-02-17': 'Chinese New Year',
                            '2026-04-01': 'Maundy Thursday',
                            '2026-04-02': 'Good Friday',
                            '2026-04-03': 'Black Saturday',
                            '2026-04-08': 'Araw ng Kagitingan',
                            '2026-04-30': 'Labor Day',
                            '2026-06-12': 'Independence Day',
                            '2026-08-21': 'Ninoy Aquino Day',
                            '2026-08-31': 'National Heroes Day',
                            '2026-11-01': 'All Saints\' Day',
                            '2026-11-30': 'Bonifacio Day',
                            '2026-12-08': 'Feast of the Immaculate Conception',
                            '2026-12-25': 'Christmas Day',
                            '2026-12-30': 'Rizal Day',
                            '2026-12-31': 'Last Day of the Year',
                        },
                    };

                    return holidayMapByYear[year] || {};
                },

                ensureWeekendBreaksBlank() {
                    this.entries = this.entries.map(entry => {
                        const isWeekend = entry.day_name === 'Sat' || entry.day_name === 'Sun';

                        if (!isWeekend) {
                            return entry;
                        }

                        return {
                            ...entry,
                            time_out_am: '',
                            time_in_pm: '',
                        };
                    });
                },

                calculateHours(index) {
                    const entry = this.entries[index];
                    let totalMinutes = 0;

                    // Calculate AM session
                    if (entry.time_in_am && entry.time_out_am) {
                        const inAm = this.timeToMinutes(entry.time_in_am);
                        const outAm = this.timeToMinutes(entry.time_out_am);
                        if (outAm > inAm) {
                            totalMinutes += outAm - inAm;
                        }
                    }

                    // Calculate PM session
                    if (entry.time_in_pm && entry.time_out_pm) {
                        const inPm = this.timeToMinutes(entry.time_in_pm);
                        const outPm = this.timeToMinutes(entry.time_out_pm);
                        if (outPm > inPm) {
                            totalMinutes += outPm - inPm;
                        }
                    }

                    if (totalMinutes > 0) {
                        const hours = Math.floor(totalMinutes / 60);
                        const mins = totalMinutes % 60;
                        entry.hours_mins = `${hours}:${mins.toString().padStart(2, '0')}`;
                    } else {
                        entry.hours_mins = '';
                    }

                    this.calculateSummary();
                },

                clearDayTimes(index) {
                    const entry = this.entries[index];
                    entry.time_in_am = '';
                    entry.time_out_am = '';
                    entry.time_in_pm = '';
                    entry.time_out_pm = '';
                    entry.hours_mins = '';
                    this.calculateSummary();
                },

                handleTabFromAmIn(event, index) {
                    if (event.shiftKey) {
                        return;
                    }

                    event.preventDefault();

                    this.$nextTick(() => {
                        const row = event.target.closest('tr');
                        const pmOutInput = row?.querySelector('input[data-field="time_out_pm"]:not([disabled])');

                        if (pmOutInput) {
                            pmOutInput.focus();
                        }
                    });
                },

                timeToMinutes(time) {
                    if (!time) return 0;
                    const [hours, mins] = time.split(':').map(Number);
                    return hours * 60 + mins;
                },

                calculateSummary() {
                    let daysWorked = 0;
                    let absences = 0;
                    let holidays = 0;
                    let legalHoliday = 0;
                    let specialHoliday = 0;

                    this.entries.forEach(entry => {
                        const isWeekend = entry.day_name === 'Sat' || entry.day_name === 'Sun';

                        if (entry.holiday_type) {
                            const holidayType = entry.holiday_type.toLowerCase();
                            if (holidayType.includes('legal')) {
                                legalHoliday++;
                            } else if (holidayType.includes('special')) {
                                specialHoliday++;
                            } else {
                                holidays++;
                            }
                        } else if (isWeekend) {
                            if (entry.time_in_am || entry.time_in_pm) {
                                daysWorked++;
                            }
                        } else if (entry.time_in_am || entry.time_in_pm) {
                            daysWorked++;
                        } else if (!isWeekend) {
                            absences++;
                        }
                    });

                    this.summary = {
                        days_worked: daysWorked,
                        absences: absences,
                        tardiness: 0,
                        undertime: 0,
                        overtime: 0,
                        leave: 0,
                        rest_day: 0,
                        ta_free: 0,
                        ut_free: 0,
                        special_holiday: specialHoliday,
                        legal_holiday: legalHoliday,
                        total_days_worked: daysWorked,
                    };
                },

                getMonthYearString() {
                    if (!this.selectedMonth) return '';
                    const [year, month] = this.selectedMonth.split('-').map(Number);
                    const monthNames = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
                        'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
                    ];
                    const daysInMonth = new Date(year, month, 0).getDate();
                    return `${monthNames[month - 1]} 1-${daysInMonth}, ${year}`;
                },

                async saveDtr() {
                    if (!this.form.employee_name || !this.form.position) {
                        this.showMessage('Please fill in employee name and position.', 'error');
                        return;
                    }

                    this.ensureWeekendBreaksBlank();

                    this.saving = true;

                    try {
                        const response = await fetch('/dtr/save', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                record_id: this.recordId,
                                employee_name: this.form.employee_name,
                                position: this.form.position,
                                official_time: this.form.official_time,
                                office_department: this.form.office_department,
                                month_year: this.getMonthYearString(),
                                record_month: this.selectedMonth + '-01',
                                entries: this.entries,
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.recordId = data.record_id;
                            this.showMessage('DTR saved successfully!', 'success');
                            // Reload page to update the records list
                            setTimeout(() => {
                                window.location.href = '/?record_id=' + this.recordId;
                            }, 1000);
                        } else {
                            this.showMessage(data.message || 'Error saving DTR.', 'error');
                        }
                    } catch (error) {
                        this.showMessage('Error saving DTR: ' + error.message, 'error');
                    }

                    this.saving = false;
                },

                async loadRecord(recordId) {
                    if (!recordId) {
                        this.clearForm();
                        return;
                    }
                    window.location.href = '/dtr/' + recordId + '/edit';
                },

                async exportPdf() {
                    if (!this.recordId) {
                        this.showMessage('Please save the DTR first before exporting.', 'error');
                        return;
                    }

                    this.exporting = true;

                    try {
                        window.open('/dtr/' + this.recordId + '/export-pdf', '_blank');
                        this.showSecondCopy = true;
                        this.showMessage('PDF generated successfully!', 'success');
                    } catch (error) {
                        this.showMessage('Error generating PDF: ' + error.message, 'error');
                    }

                    this.exporting = false;
                },

                async deleteRecord() {
                    if (!this.recordId) return;

                    if (!confirm('Are you sure you want to delete this DTR record?')) return;

                    try {
                        const response = await fetch('/dtr/' + this.recordId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showMessage('DTR deleted successfully!', 'success');
                            setTimeout(() => {
                                window.location.href = '/';
                            }, 1000);
                        } else {
                            this.showMessage(data.message || 'Error deleting DTR.', 'error');
                        }
                    } catch (error) {
                        this.showMessage('Error deleting DTR: ' + error.message, 'error');
                    }
                },

                clearForm() {
                    this.form = {
                        employee_name: '',
                        position: '',
                        official_time: 'FLEXITIME',
                        office_department: 'GASS - ICTD',
                    };
                    this.recordId = null;
                    this.generateEntries();
                },

                showMessage(msg, type) {
                    this.message = msg;
                    this.messageType = type;
                    setTimeout(() => {
                        this.message = '';
                    }, 5000);
                },
            };
        }
    </script>
@endpush
