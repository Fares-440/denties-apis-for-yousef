<?php

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/firebase',[FirebaseController::class,'index']);

Route::get('download',[ReportController::class,'downloadPdf'])
->name('download.tes');

Route::get('downloadReport/{id}',[ReportController::class,'downloadReport'])
->name('downloadReport');

Route::get('blockedStudent',[ReportController::class,'generateBlockedUsersPDF'])
->name('blockedStudent.tes');
