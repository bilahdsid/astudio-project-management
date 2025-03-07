<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimesheetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// // Protect the following routes with auth:api middleware
 Route::middleware('auth:api')->group(function () {

     Route::post('/logout', [AuthController::class, 'logout']);

    // User CRUD (if needed)
    Route::get('/users',      [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    //Route::post('/users',     [UserController::class, 'store']);
    //Route::put('/users/{id}', [UserController::class, 'update']);
    //Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Projects CRUD
    Route::get('/projects',      [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::post('/projects',     [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // Timesheets CRUD
    Route::get('/timesheets',      [TimesheetController::class, 'index']);
    Route::get('/timesheets/{id}', [TimesheetController::class, 'show']);
    Route::post('/timesheets',     [TimesheetController::class, 'store']);
    Route::put('/timesheets/{id}', [TimesheetController::class, 'update']);
    Route::delete('/timesheets/{id}', [TimesheetController::class, 'destroy']);

    // Attribute management endpoints
    Route::get('/attributes',      [AttributeController::class, 'index']);
    Route::get('/attributes/{id}', [AttributeController::class, 'show']);
    Route::post('/attributes',     [AttributeController::class, 's255445+++
    
    tore']);
    Route::put('/attributes/{id}', [AttributeController::class, 'update']);
    Route::delete('/attributes/{id}', [AttributeController::class, 'destroy']);
});
