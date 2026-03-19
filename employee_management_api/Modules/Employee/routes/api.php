<?php

use Illuminate\Support\Facades\Route;
use Modules\Employees\app\Http\Controllers\EmployeeController;

Route::prefix('employees')->group(function () {
    Route::post('/',          [EmployeeController::class, 'store']);   // Add
    Route::get('/',           [EmployeeController::class, 'index']);   // View All
    Route::put('/{id}',       [EmployeeController::class, 'update']);  // Edit
    Route::delete('/{id}',    [EmployeeController::class, 'destroy']); // Delete
    Route::get('/search',     [EmployeeController::class, 'search']);  // Search by phone
});