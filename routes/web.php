<?php

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
Auth::routes();


Route::middleware('auth')->group(function () {
    //Jobs
    Route::resource('jobs', App\Http\Controllers\JobController::class);
    Route::patch('jobs/{job}/current-job', [App\Http\Controllers\JobController::class, 'UpdateCurrentJob'])
        ->name('jobs.current.update');

    // Wages
    Route::get('wages/{job}/edit', [App\Http\Controllers\WageController::class, 'edit'])
        ->name('wages.edit');
    Route::patch('wages/{job}', [App\Http\Controllers\WageController::class, 'update'])
        ->name('wages.update');

    //Overtime
    Route::get('overtime/{job}/edit', [App\Http\Controllers\OvertimeController::class, 'edit'])
        ->name('overtime.edit');
    Route::patch('overtime/{job}', [App\Http\Controllers\OvertimeController::class, 'update'])
        ->name('overtime.update');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
