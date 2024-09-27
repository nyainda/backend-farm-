<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SendEmailVerificationNotificationController;
use App\Http\Controllers\Auth\SendPasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FeedingController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BreedingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\BillOfSaleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\YieldRecordController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
Route::post('/forgot-password', SendPasswordResetLinkController::class)->name('password.email');
Route::post('/reset-password', ResetPasswordController::class)->name('password.update');

// Google Authentication
Route::get('login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Authenticated routes using auth:api middleware
Route::middleware('auth:api')->group(function () {
    Route::post('/email/verification-notification', SendEmailVerificationNotificationController::class)
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Animal management routes
    Route::apiResource('animals', AnimalController::class);
    Route::apiResource('animals/{animal}/employees', EmployeeController::class);
    Route::apiResource('animals/{animal}/feedings', FeedingController::class);
    Route::apiResource('animals/{animal}/treatments', TreatmentController::class);
    Route::apiResource('animals/{animal}/suppliers', SupplierController::class);
    Route::apiResource('animals/{animal}/breedings', BreedingController::class);
    Route::apiResource('animals/{animal}/health', HealthController::class);
    Route::apiResource('animals/{animal}/bill-of-sale', BillOfSaleController::class);
    Route::apiResource('animals/{animal}/contacts', ContactController::class);
    Route::apiResource('animals/{animal}/measurements', MeasurementController::class);
    Route::apiResource('animals/{animal}/yield-records', YieldRecordController::class);
    Route::apiResource('animals/{animal}/notes', NoteController::class);

    Route::apiResource('animals/{animal}/tasks', TaskController::class);

    // Notifications
    Route::get('notifications/{animal_id}/pending', [NotificationController::class, 'showPendingNotifications'])
        ->name('notifications.pending');

    // AnimalContent routes
    Route::get('AnimalContent', [AnimalController::class, 'index'])->name('index');
    Route::get('AnimalContent/showallsuppliers', [AnimalController::class, 'showAllSuppliers'])->name('AnimalContent.showallsuppliers');
    Route::get('AnimalContent/showallhealths', [AnimalController::class, 'showAllHealths'])->name('AnimalContent.showallhealths');
    Route::get('AnimalContent/showalltreatments', [AnimalController::class, 'showAllTreatments'])->name('AnimalContent.showalltreatments');
    Route::get('AnimalContent/showallfeedings', [AnimalController::class, 'showAllFeedings'])->name('AnimalContent.showallfeedings');
    Route::get('AnimalContent/showallmeasurements', [AnimalController::class, 'showAllMeasurements'])->name('AnimalContent.showallmeasurements');
    Route::get('AnimalContent/showallyieldrecords', [AnimalController::class, 'showAllYieldrecords'])->name('AnimalContent.showallyieldrecords');
    Route::get('AnimalContent/showallnotes', [AnimalController::class, 'showAllnotes'])->name('AnimalContent.showallnotes');
    Route::get('/Task/showalltasks', [AnimalController::class, 'showAlltasks'])->name('Task.showalltasks');
    Route::get('/Task/showallcontact', [AnimalController::class, 'showallcontact'])->name('Task.showallcontact');
});
