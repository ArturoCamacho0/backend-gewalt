<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskController;


Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('users', UserController::class);
    Route::resource('clients', ClientController::class);

    Route::resource('companies', CompanyController::class);

    Route::resource('projects', ProjectController::class);
    Route::get('projects/active', [ProjectController::class, 'getActiveProjects']);
    Route::get('projects/inactive', [ProjectController::class, 'getInactiveProjects']);
    Route::get('projects/active/amount', [ProjectController::class, 'getActiveProjectsCount']);
    Route::get('projects/inactive/amount', [ProjectController::class, 'getInactiveProjectsCount']);
    Route::delete('projects/{projectId}/tasks', [ProjectController::class, 'deleteTasks']);
    Route::get('upcoming/projects', [ProjectController::class, 'getUpcomingProjects']);

    Route::resource('services', ServiceController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::resource('tasks', TaskController::class);
    Route::get('tasks/pending/count', [TaskController::class, 'countPendingAndInProgressTasks']);
});
