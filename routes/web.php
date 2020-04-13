<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramEditionController;
use App\Http\Controllers\ProgramEditionExportController;
use App\Http\Controllers\ProgramEditionStudentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentExportController;
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

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::apiResource('/programs', ProgramController::class)->names('programs');

    Route::apiResource('/program-editions.students', ProgramEditionStudentController::class);
    Route::get('/program-editions/{programEdition}/export', ProgramEditionExportController::class);
    Route::apiResource('/program-editions', ProgramEditionController::class);

    Route::apiResource('/companies', CompanyController::class);

    Route::get('/students/{student}/export', StudentExportController::class);
    Route::resource('/students', StudentController::class);

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::apiResource('users', UserController::class)->names('users');
    });
});


// Authentication routes
Route::get('login', [LoginController::class, 'showloginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
// Reset password routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
