<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return redirect('/personal');
});

// se manda a llamar la ruta para descargar el archivo 
Route::get('/pdf/generate/timesheet/{user}',[PdfController::class,'TimesheetRecords'])->name('pdf.example');