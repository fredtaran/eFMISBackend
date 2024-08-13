<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('generate-report', [ReportController::class, 'generatePdf']);