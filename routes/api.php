<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except('index');
    Route::apiResource('companies', CompanyController::class);
    Route::get('employees/{company}', [EmployeeController::class, 'index'])->name('employees.index');
    Route::apiResource('employees', EmployeeController::class)->except('index');

});
