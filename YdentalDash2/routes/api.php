<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\UniversityController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\TheCaseController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Middleware\FirebaseAuthMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// المسارات العامة التي لا تحتاج للمصادقة
Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login'); // Added ->name('login')

// وضع مسارات تسجيل الدخول والـ OTP خارج مجموعة auth:sanctum
Route::post('/patients/login', [PatientController::class, 'login']);
Route::post('/patient/password/request-otp', [PatientController::class, 'requestOtp']);
Route::post('/patient/password/reset-with-otp', [PatientController::class, 'resetPasswordWithOtp']);
Route::post('/students/login', [StudentController::class, 'login']);

    // مسارات الطلاب
    Route::controller(StudentController::class)->group(function () {
        Route::get('/students/by-service/{serviceId}', 'getStudentsByService');
        Route::get('/students/select', 'select');
    });
Route::apiResource('students', StudentController::class);
// مسارات المرضى
Route::controller(PatientController::class)->group(function () {
    Route::get('/students/{studentId}/patients', 'getPatientsByStudentId');
    Route::get('/patients/select', 'select');
});
Route::apiResource('patients', PatientController::class);


// باقي المسارات المحمية بواسطة Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // مسارات الزيارات
    Route::get('/visits/select', [VisitController::class, 'select']);
    Route::get('/visits/today', [VisitController::class, 'getTodaysVisits']);
    Route::get('/visits/today/count', [VisitController::class, 'getTodaysVisitsCount']);
    Route::apiResource('visits', VisitController::class);



    // مسارات الخدمات
    Route::get('/services/select', [ServiceController::class, 'select']);
    Route::apiResource('services', ServiceController::class);

    // مسارات الشكاوى
    Route::get('/complaints/select', [ComplaintController::class, 'select']);
    Route::apiResource('complaints', ComplaintController::class);

    // مسارات الجداول (Schedules)
    Route::get('/schedules/select', [ScheduleController::class, 'select']);
    Route::apiResource('schedules', ScheduleController::class);

    // مسارات الدردشات (Chats)
    Route::get('/chats/select', [ChatController::class, 'select']);
    Route::apiResource('chats', ChatController::class);

    // مسارات التقييمات (Reviews)
    Route::get('/reviews/select', [ReviewController::class, 'select']);
    Route::apiResource('reviews', ReviewController::class);

    // مسارات المدن
    Route::get('/cities/select', [CityController::class, 'select']);
    Route::apiResource('cities', CityController::class);

    // مسارات الجامعات
    Route::get('/universities/select', [UniversityController::class, 'select']);
    Route::apiResource('universities', UniversityController::class);

    // مسارات المواعيد
    Route::get('/appointments/select', [AppointmentController::class, 'select']);
    Route::apiResource('appointments', AppointmentController::class);

    // مسارات الحالات الخاصة
    Route::controller(TheCaseController::class)->group(function () {
        Route::get('/thecases/select', 'select');
        Route::post('/create-case-with-schedule', 'createCaseWithSchedule');
        Route::put('/update-case-with-schedule/{caseId}', 'updateCaseWithSchedule');
    });
    Route::apiResource('thecases', TheCaseController::class);

    // مسارات التقارير
    Route::apiResource('reports', ReportController::class);

    // تفاصيل المستخدم (بعد المصادقة)
    Route::get('/user', [AuthController::class, 'userDetails']);
});

// Apply Firebase middleware to specific routes
// Route::middleware([FirebaseAuthMiddleware::class])->group(function () {
// });


// use App\Http\Controllers\Api\FirebaseAuthCustomController;

// // Firebase Authentication Routes
// Route::post('/firebase/register', [FirebaseAuthCustomController::class, 'register']);
// Route::post('/firebase/login', [FirebaseAuthCustomController::class, 'login']);
// Route::middleware('auth:firebase')->get('/firebase/user', [FirebaseAuthCustomController::class, 'userDetails']);
