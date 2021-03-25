<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    // User
    Route::get('users/edit-timezone', [App\Http\Controllers\UserController::class, 'editTimeZone'])
        ->name('users.edit-timezone');
    Route::patch('users/update-timezone', [App\Http\Controllers\UserController::class, 'UpdateTimeZone'])
        ->name('users.update-timezone');

    //Jobs
    Route::resource('jobs', App\Http\Controllers\JobController::class);
    Route::patch('jobs/{job}/current-job', [App\Http\Controllers\JobController::class, 'UpdateCurrentJob'])
        ->name('jobs.current.update');

    // Wages
    Route::get('wages/{job}/edit', [App\Http\Controllers\WageController::class, 'edit'])
        ->name('wages.edit');
    Route::patch('wages/{job}', [App\Http\Controllers\WageController::class, 'update'])
        ->name('wages.update');
    Route::get('wages/{job}/pay-period', [App\Http\Controllers\WageController::class, 'getPayPeriod'])
        ->name('wages.pay-period');
    Route::get('wages/{job}/export/{fileExtension}', [App\Http\Controllers\WageController::class, 'export'])
        ->name('wages.export');

    // Overtime
    Route::get('overtime/{job}/edit', [App\Http\Controllers\OvertimeController::class, 'edit'])
        ->name('overtime.edit');
    Route::patch('overtime/{job}', [App\Http\Controllers\OvertimeController::class, 'update'])
        ->name('overtime.update');

    // Shift Differential
    Route::get('shift-differentials/{job}/edit', [App\Http\Controllers\ShiftDifferentialController::class, 'edit'])
        ->name('shift-differentials.edit');
    Route::patch('shift-differentials/{job}', [App\Http\Controllers\ShiftDifferentialController::class, 'update'])
        ->name('shift-differentials.update');

    // Shifts
    Route::get('shifts/{job}/index', [App\Http\Controllers\ShiftController::class, 'index'])->name('shifts.index');
    Route::match(['post', 'get'],'shifts/{job}/search', [App\Http\Controllers\ShiftController::class, 'search'])->name('shifts.search');
    Route::resource('shifts', App\Http\Controllers\ShiftController::class)->only('create');

    // Tracking
    Route::get('tracking/{job}/edit', [App\Http\Controllers\TrackingController::class, 'edit'])
        ->name('tracking.edit');
    Route::patch('tracking/{job}', [App\Http\Controllers\TrackingController::class, 'update'])
        ->name('tracking.update');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
