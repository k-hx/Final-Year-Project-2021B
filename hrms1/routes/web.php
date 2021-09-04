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
Route::get('leaveTypes', [App\Http\Controllers\LeaveTypeController::class, 'show'])->name('showLeaveTypes');

// edit leave type -----------------------------------------------------------
Route::get('leaveTypes/edit/{id}', [App\Http\Controllers\LeaveTypeController::class, 'edit'])->name('editLeaveType');

Route::post('leaveType/update', [App\Http\Controllers\LeaveTypeController::class, 'update'])->name('updateLeaveType');

// delete leave type -----------------------------------------------------------
Route::get('leaveType/delete/{id}', [App\Http\Controllers\LeaveTypeController::class, 'delete'])->name('deleteLeaveType');

// create leave grade -----------------------------------------------------------
Route::get('createLeaveGrade', [App\Http\Controllers\LeaveGradeController::class, 'showCreatePage'])->name('deleteLeaveType');

Route::post('createLeaveGrade/store', [App\Http\Controllers\LeaveGradeController::class, 'store'])->name('addLeaveGrade');
