<?php

use App\Http\Controllers\DtrController;
use Illuminate\Support\Facades\Route;

// DTR Routes
Route::get('/', [DtrController::class, 'index'])->name('dtr.index');
Route::post('/dtr/save', [DtrController::class, 'save'])->name('dtr.save');
Route::get('/dtr/month-entries', [DtrController::class, 'getMonthEntries'])->name('dtr.month-entries');
Route::get('/dtr/records', [DtrController::class, 'getRecords'])->name('dtr.records');
Route::get('/dtr/{id}/edit', [DtrController::class, 'edit'])->name('dtr.edit');
Route::get('/dtr/{id}/export-pdf', [DtrController::class, 'exportPdf'])->name('dtr.export-pdf');
Route::delete('/dtr/{id}', [DtrController::class, 'destroy'])->name('dtr.destroy');
