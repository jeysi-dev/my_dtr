<?php

namespace App\Http\Controllers;

use App\Models\DtrRecord;
use App\Models\DtrEntry;
use App\Services\DtrPdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DtrController extends Controller
{
    /**
     * Display the DTR form.
     */
    public function index(Request $request)
    {
        $records = DtrRecord::orderBy('created_at', 'desc')->get();
        $selectedRecord = null;

        if ($request->has('record_id')) {
            $selectedRecord = DtrRecord::with('entries')->find($request->record_id);
        }

        return view('dtr.form', compact('records', 'selectedRecord'));
    }

    /**
     * Get entries for a specific month (AJAX).
     */
    public function getMonthEntries(Request $request)
    {
        $month = $request->input('month'); // Format: 2026-01
        $recordId = $request->input('record_id');

        if ($recordId) {
            $record = DtrRecord::with('entries')->find($recordId);
            if ($record) {
                return response()->json([
                    'success' => true,
                    'record' => $record,
                    'entries' => $record->entries,
                ]);
            }
        }

        // Generate empty entries for the month
        $date = Carbon::parse($month . '-01');
        $daysInMonth = $date->daysInMonth;
        $entries = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = $date->copy()->day($day);
            $entries[] = [
                'day' => $day,
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $currentDate->format('D'),
                'time_in_am' => null,
                'time_out_am' => null,
                'time_in_pm' => null,
                'time_out_pm' => null,
                'hours_mins' => null,
                'is_holiday' => false,
                'holiday_type' => null,
                'remarks' => null,
            ];
        }

        return response()->json([
            'success' => true,
            'entries' => $entries,
        ]);
    }

    /**
     * Save DTR data.
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'official_time' => 'nullable|string|max:255',
            'office_department' => 'required|string|max:255',
            'month_year' => 'required|string',
            'record_month' => 'required|date',
            'entries' => 'required|array',
            'entries.*.day' => 'required|integer|min:1|max:31',
            'entries.*.date' => 'required|date',
            'entries.*.day_name' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create or update the DTR record
            $recordData = [
                'employee_name' => $request->employee_name,
                'position' => $request->position,
                'official_time' => $request->official_time ?? 'FLEXITIME',
                'office_department' => $request->office_department,
                'month_year' => $request->month_year,
                'record_month' => $request->record_month,
            ];

            if ($request->has('record_id') && $request->record_id) {
                $record = DtrRecord::findOrFail($request->record_id);
                $record->update($recordData);
                // Delete existing entries
                $record->entries()->delete();
            } else {
                $record = DtrRecord::create($recordData);
            }

            // Create entries
            foreach ($request->entries as $entryData) {
                $entry = new DtrEntry([
                    'dtr_record_id' => $record->id,
                    'day' => $entryData['day'],
                    'date' => $entryData['date'],
                    'day_name' => $entryData['day_name'],
                    'time_in_am' => $this->parseTime($entryData['time_in_am'] ?? null),
                    'time_out_am' => $this->parseTime($entryData['time_out_am'] ?? null),
                    'time_in_pm' => $this->parseTime($entryData['time_in_pm'] ?? null),
                    'time_out_pm' => $this->parseTime($entryData['time_out_pm'] ?? null),
                    'is_holiday' => $entryData['is_holiday'] ?? false,
                    'holiday_type' => $entryData['holiday_type'] ?? null,
                    'remarks' => $entryData['remarks'] ?? null,
                ]);

                // Calculate hours
                $entry->hours_mins = $entry->calculateHours();
                $entry->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'DTR saved successfully!',
                'record_id' => $record->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving DTR: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Parse time input to standard format.
     */
    private function parseTime(?string $time): ?string
    {
        if (!$time || trim($time) === '') {
            return null;
        }

        try {
            return Carbon::parse($time)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Edit an existing DTR record.
     */
    public function edit($id)
    {
        $records = DtrRecord::orderBy('created_at', 'desc')->get();
        $selectedRecord = DtrRecord::with('entries')->findOrFail($id);

        return view('dtr.form', compact('records', 'selectedRecord'));
    }

    /**
     * Delete a DTR record.
     */
    public function destroy($id)
    {
        try {
            $record = DtrRecord::findOrFail($id);
            $record->delete();

            return response()->json([
                'success' => true,
                'message' => 'DTR record deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting DTR: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export DTR to PDF.
     */
    public function exportPdf($id, DtrPdfService $pdfService)
    {
        $record = DtrRecord::with('entries')->findOrFail($id);

        return $pdfService->generate($record);
    }

    /**
     * Get list of saved DTR records.
     */
    public function getRecords()
    {
        $records = DtrRecord::orderBy('created_at', 'desc')
            ->get(['id', 'employee_name', 'month_year', 'created_at']);

        return response()->json([
            'success' => true,
            'records' => $records,
        ]);
    }
}
