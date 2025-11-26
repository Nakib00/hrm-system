<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('employees.index');
    })->name('dashboard');

    Route::resource('departments', DepartmentController::class)->only(['index', 'create', 'store']);
    Route::resource('skills', SkillController::class)->only(['index', 'create', 'store']);


    Route::get('/employees/filter', [EmployeeController::class, 'filter'])->name('employees.filter');
    Route::get('/employees/check-email', [EmployeeController::class, 'checkEmail'])->name('employees.checkEmail');


    Route::resource('employees', EmployeeController::class);
});

require __DIR__ . '/auth.php';
