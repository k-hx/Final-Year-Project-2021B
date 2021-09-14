<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// create leave type -----------------------------------------------------------
Route::get('createLeaveType', function() {
   return view('admin/createLeaveType');
})->name('createLeaveType')->middleware('auth');

Route::post('createLeaveType/store', [App\Http\Controllers\LeaveTypeController::class, 'store'])->name('addLeaveType')->middleware('auth');

// display leave types -----------------------------------------------------------
Route::get('leaveType', [App\Http\Controllers\LeaveTypeController::class, 'show'])->name('showLeaveTypes');

// edit leave type -----------------------------------------------------------
Route::get('leaveType/edit/{id}', [App\Http\Controllers\LeaveTypeController::class, 'edit'])->name('editLeaveType');

Route::post('leaveType/update', [App\Http\Controllers\LeaveTypeController::class, 'update'])->name('updateLeaveType');

// delete leave type -----------------------------------------------------------
Route::get('leaveType/delete/{id}', [App\Http\Controllers\LeaveTypeController::class, 'delete'])->name('deleteLeaveType');

// create leave grade -----------------------------------------------------------
Route::get('createLeaveGrade', function() {
   return view('admin/createLeaveGrade');
})->name('createLeaveGrade');

Route::post('createLeaveGrade/store', [App\Http\Controllers\LeaveGradeController::class, 'store'])->name('addLeaveGrade');

// show leave grade -----------------------------------------------------------
Route::get('leaveGrade', [App\Http\Controllers\LeaveGradeController::class, 'show'])->name('showLeaveGrades');

// edit leave grade name -----------------------------------------------------------
Route::get('leaveGrade/editLeaveGradeName/{id}', [App\Http\Controllers\LeaveGradeController::class, 'edit'])->name('editLeaveGradeName');

Route::post('leaveGrade/updateLeaveGradeName', [App\Http\Controllers\LeaveGradeController::class, 'update'])->name('updateLeaveGradeName');

// delete leave grade -----------------------------------------------------------
Route::get('leaveGrade/delete/{id}', [App\Http\Controllers\LeaveGradeController::class, 'delete'])->name('deleteLeaveGrade');

// show leave entitlement of a leave grade -----------------------------------------------------------
Route::get('leaveGrade/leaveEntitlement/{id}', [App\Http\Controllers\LeaveEntitlementController::class, 'show'])->name('leaveEntitlement');

Route::post('leaveGrade/leaveEntitlement/{id}/add', [App\Http\Controllers\LeaveEntitlementController::class, 'addLeaveEntitlement'])->name('addLeaveEntitlement');

// edit a leave entitlement -----------------------------------------------------------
Route::get('leaveGrade/editLeaveEntitlement/{leaveGradeId}/{id}', [App\Http\Controllers\LeaveEntitlementController::class, 'edit'])->name('editLeaveEntitlement');

Route::post('leaveGrade/leaveEntitlement/edit/{leaveGradeId}/{id}', [App\Http\Controllers\LeaveEntitlementController::class, 'updateLeaveEntitlement'])->name('updateLeaveEntitlement');

// delete a leave entitlement -----------------------------------------------------------
Route::get('leaveGrade/deleteLeaveEntitlement/{leaveGradeId}/{id}', [App\Http\Controllers\LeaveEntitlementController::class, 'deleteLeaveEntitlement'])->name('deleteLeaveEntitlement');

// apply leave -----------------------------------------------------------
Route::get('applyLeave', [App\Http\Controllers\LeaveApplicationController::class, 'showApplyLeavePage'])->name('showApplyLeavePage');

Route::post('applyLeave/submit', [App\Http\Controllers\LeaveApplicationController::class, 'submitApplication'])->name('submitApplication');

// leave application list -----------------------------------------------------------
Route::get('leaveApplicationList', [App\Http\Controllers\LeaveApplicationController::class, 'showLeaveApplicationList'])->name('showLeaveApplicationList');

// leave application list admin -----------------------------------------------------------
Route::get('admin/leaveApplicationList', [App\Http\Controllers\LeaveApplicationController::class, 'showLeaveApplicationListAdmin'])->name('showLeaveApplicationListAdmin');

// approve leave -----------------------------------------------------------
Route::get('admin/approveLeave/{employeeId}/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'approve'])->name('approveLeave');

// approve multiple leave -----------------------------------------------------------
Route::get('admin/approveMultipleLeave', [App\Http\Controllers\LeaveApplicationController::class, 'approveMultiple'])->name('approveMultipleLeave');

// reject leave -----------------------------------------------------------
Route::get('admin/reject/{employeeId}/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('rejectLeave');

// reject multiple leave -----------------------------------------------------------
Route::get('admin/rejectMultipleLeave', [App\Http\Controllers\LeaveApplicationController::class, 'rejectMultiple'])->name('rejectMultipleLeave');

// cancel leave -----------------------------------------------------------
Route::get('leaveApplication/cancel/{employeeId}/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'cancel'])->name('cancelLeave');

// all employees' leave grade -----------------------------------------------------------
Route::get('employeesLeave/all', [App\Http\Controllers\LeaveGradeController::class, 'showAllEmployeesLeaveGrade'])->name('allEmployeesLeaveGrade');

// an employee's leave grade -----------------------------------------------------------
Route::get('employeesLeave/{id}/{leaveGradeId}',[App\Http\Controllers\LeaveGradeController::class, 'showAnEmployeesLeave'])->name('employeesLeaveGrade');

// view own leave grade (employee) -----------------------------------------------------------
Route::get('employeesLeave',[App\Http\Controllers\EmployeeLeaveController::class, 'showEmployeeOwnLeave'])->name('employeeOwnLeaveGrade');

// assign leave grade -----------------------------------------------------------
Route::get('setEmployeesLeaveGrade/{id}',[App\Http\Controllers\LeaveGradeController::class, 'setEmployeesLeaveGradePage'])->name('setEmployeesLeaveGrade');

Route::post('setEmployeesLeaveGrade/update',[App\Http\Controllers\LeaveGradeController::class, 'updateEmployeesLeaveGrade'])->name('updateEmployeesLeaveGrade');

// create new employees leave record every year (manually) -----------------------------------------------------------
Route::get('employeesLeave/createLeaveRecord',[App\Http\Controllers\EmployeeLeaveController::class, 'createLeaveRecord'])->name('createLeaveRecord');
