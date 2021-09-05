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
Route::get('createLeaveGrade', [App\Http\Controllers\LeaveGradeController::class, 'show'])->name('createLeaveGrade');

Route::post('createLeaveGrade/store', [App\Http\Controllers\LeaveGradeController::class, 'store'])->name('addLeaveGrade');

// show leave grade -----------------------------------------------------------
Route::get('leaveGrades', [App\Http\Controllers\LeaveGradeController::class, 'show'])->name('showLeaveGrades');

// edit leave grade name -----------------------------------------------------------
Route::get('leaveGrade/editLeaveGradeName/{id}', [App\Http\Controllers\LeaveGradeController::class, 'edit'])->name('editLeaveGradeName');

Route::post('leaveGrade/updateLeaveGradeName', [App\Http\Controllers\LeaveGradeController::class, 'update'])->name('updateLeaveGradeName');

// delete leave grade -----------------------------------------------------------
Route::get('leaveGrade/delete/{id}', [App\Http\Controllers\LeaveGradeController::class, 'delete'])->name('deleteLeaveGrade');

// edit leave entitlement -----------------------------------------------------------
Route::get('leaveGrade/leaveEntitlement/{id}', [App\Http\Controllers\LeaveEntitlementController::class, 'show'])->name('leaveEntitlement');

Route::post('leaveGrade/leaveEntitlement/{id}/add', [App\Http\Controllers\LeaveEntitlementController::class, 'addLeaveEntitlement'])->name('addLeaveEntitlement');

// apply leave -----------------------------------------------------------
Route::get('applyLeave', [App\Http\Controllers\LeaveApplicationController::class, 'showApplyLeavePage'])->name('showApplyLeavePage');

Route::post('applyLeave/submit', [App\Http\Controllers\LeaveApplicationController::class, 'submitApplication'])->name('submitApplication');

// leave application list -----------------------------------------------------------
Route::get('leaveApplicationList', [App\Http\Controllers\LeaveApplicationController::class, 'showLeaveApplicationList'])->name('showLeaveApplicationList');

// leave application list admin -----------------------------------------------------------
Route::get('admin/leaveApplicationList', [App\Http\Controllers\LeaveApplicationController::class, 'showLeaveApplicationListAdmin'])->name('showLeaveApplicationListAdmin');

// approve leave -----------------------------------------------------------
Route::get('admin/approveLeave/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'approve'])->name('approveLeave');

// reject leave -----------------------------------------------------------
Route::get('admin/reject/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('rejectLeave');

// cancel leave -----------------------------------------------------------
Route::get('leaveApplication/cancel/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'cancel'])->name('cancelLeave');
