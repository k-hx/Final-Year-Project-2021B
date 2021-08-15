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
})->name('createLeaveType')->middleware('auth');

Route::post('createLeaveType/store', [App\Http\Controllers\LeaveTypeController::class, 'store'])->name('addLeaveType')->middleware('auth');

// display leave types -----------------------------------------------------------
Route::get('leaveTypes', [App\Http\Controllers\LeaveTypeController::class, 'show'])->name('showLeaveTypes')->middleware('auth');

// edit leave type -----------------------------------------------------------
Route::get('leaveTypes/edit/{id}', [App\Http\Controllers\LeaveTypeController::class, 'edit'])->name('editLeaveType')->middleware('auth');

Route::post('leaveType/update', [App\Http\Controllers\LeaveTypeController::class, 'update'])->name('updateLeaveType')->middleware('auth');

// delete leave type -----------------------------------------------------------
Route::get('leaveType/delete/{id}', [App\Http\Controllers\LeaveTypeController::class, 'delete'])->name('deleteLeaveType')->middleware('auth');
