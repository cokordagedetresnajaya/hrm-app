<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/create', [CompanyController::class, 'create'])->name('create');
        Route::post('/create', [CompanyController::class, 'store'])->name('store');
        Route::post('/switch', [CompanyController::class, 'switch'])->name('switch');
        Route::delete('/{id}/delete', [CompanyController::class, 'delete'])->where('id', '[0-9]+')->name('delete');
        Route::get('/{id}/edit', [CompanyController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
        Route::patch('/{id}/edit', [CompanyController::class, 'update'])->where('id', '[0-9]+')->name('update');
    });
    Route::middleware('company.context')->group(function () {
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/create', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DepartmentController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
            Route::patch('/{id}/edit', [DepartmentController::class, 'update'])->where('id', '[0-9]+')->name('update');
            Route::delete('/{id}/delete', [DepartmentController::class, 'delete'])->where('id', '[0-9]+')->name('delete');
        });
        Route::prefix('designations')->name('designations.')->group(function () {
            Route::get('/', [DesignationController::class, 'index'])->name('index');
            Route::get('/create', [DesignationController::class, 'create'])->name('create');
            Route::post('/create', [DesignationController::class, 'store'])->name('store');
            Route::get('/by-department/{id}', [DesignationController::class, 'getByDepartment'])->where('id', '[0-9]+');
            Route::get('/{id}/edit', [DesignationController::class, 'edit'])->where('id', '[0-9]+')->name('edit');
            Route::patch('/{id}/edit', [DesignationController::class, 'update'])->where('id', '[0-9]+')->name('update');
            Route::delete('/{id}/delete', [DesignationController::class, 'delete'])->where('id', '[0-9]+')->name('delete');
        });
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/get-employees-autocomplete', [EmployeeController::class, 'getEmployeesAutocomplete'])->name('getEmployeesAutocomplete');
            Route::get('/create', [EmployeeController::class, 'create'])->name('create');
            Route::post('/create', [EmployeeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('edit');
            Route::patch('/{id}/edit', [EmployeeController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [EmployeeController::class, 'delete'])->name('delete');
        });
        Route::prefix('contracts')->name('contracts.')->group(function () {
            Route::get('/', [ContractController::class, 'index'])->name('index');
            Route::get('/create', [ContractController::class, 'create'])->name('create');
            Route::post('/create', [ContractController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ContractController::class, 'edit'])->name('edit');
            Route::patch('/{id}/edit', [ContractController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [ContractController::class, 'delete'])->name('delete');
        });
        Route::prefix('payrolls')->name('payrolls.')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
            Route::get('/{id}/show', [PayrollController::class, 'show'])->name('show');
        });
    });
});

require __DIR__ . '/auth.php';
