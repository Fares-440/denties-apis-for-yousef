<?php

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
