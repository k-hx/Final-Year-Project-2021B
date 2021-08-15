<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// create leave type -----------------------------------------------------------
Route::get('createLeaveType', function() {
   return view('createLeaveType');
})->middleware('auth');

Route::post('createLeaveType/store', [App\Http\Controllers\LeaveTypeController::class, 'store'])->name('addLeaveType');

// display leave types -----------------------------------------------------------
Route::get('leaveTypes', [App\Http\Controllers\LeaveTypeController::class, 'show'])->name('showLeaveTypes');
