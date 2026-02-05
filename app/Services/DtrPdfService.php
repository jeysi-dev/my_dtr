<?php

namespace App\Services;

use App\Models\DtrRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class DtrPdfService
{
    /**
     * Generate PDF for a DTR record.
     */
    public function generate(DtrRecord $record): Response
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '256M');

        $summary = $record->getSummary();

        $pdf = Pdf::loadView('dtr.pdf', [
            'record' => $record,
            'summary' => $summary,
        ]);

        // Set paper size to Letter (8.5 x 11 inches) and options
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);

        // Generate filename
        $filename = sprintf(
            'DTR_%s_%s.pdf',
            str_replace(' ', '_', $record->employee_name),
            str_replace([' ', ','], ['_', ''], $record->month_year)
        );

        return $pdf->download($filename);
    }

    /**
     * Generate PDF and return as stream (for preview).
     */
    public function stream(DtrRecord $record): Response
    {
        // Increase memory limit for PDF generation
        ini_set('memory_limit', '256M');

        $summary = $record->getSummary();

        $pdf = Pdf::loadView('dtr.pdf', [
            'record' => $record,
            'summary' => $summary,
        ]);

        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isFontSubsettingEnabled', true);

        return $pdf->stream('dtr_preview.pdf');
    }
}
