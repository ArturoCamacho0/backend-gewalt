<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'api'], function () {
    Route::resource('clients', ClientController::class);

    Route::resource('companies', CompanyController::class);

    Route::resource('projects', ProjectController::class);
    Route::get('projects/active', [ProjectController::class, 'getActiveProjects']);
    Route::get('projects/inactive', [ProjectController::class, 'getInactiveProjects']);
    Route::get('projects/active/amount', [ProjectController::class, 'getActiveProjectsCount']);
    Route::get('projects/inactive/amount', [ProjectController::class, 'getInactiveProjectsCount']);

    Route::resource('services', ServiceController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::resource('tasks', TaskController::class);
});
