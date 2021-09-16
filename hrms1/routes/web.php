<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//++++++++++++++++++++++++++++++++++++++++++++++++++++ LEAVE MANAGEMENT ++++++++++++++++++++++++++++++++++++++++++++++++++
//+++++++++++++++++++++++++++++++++++++++++++++++ ROUTE FOR ADMINISTRATORS +++++++++++++++++++++++++++++++++++++++++++++++

// create leave type -----------------------------------------------------------
Route::get('createLeaveType', function() {
   return view('admin/leave/createLeaveType');
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
   return view('admin/leave/createLeaveGrade');
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

// leave application list -----------------------------------------------------------
Route::get('admin/leaveApplicationList', [App\Http\Controllers\LeaveApplicationController::class, 'showLeaveApplicationListAdmin'])->name('showLeaveApplicationListAdmin');

// approve leave -----------------------------------------------------------
Route::get('admin/approveLeave/{employeeId}/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'approve'])->name('approveLeave');

// approve multiple leave -----------------------------------------------------------
Route::get('admin/approveMultipleLeave', [App\Http\Controllers\LeaveApplicationController::class, 'approveMultiple'])->name('approveMultipleLeave');

// reject leave -----------------------------------------------------------
Route::get('admin/reject/{employeeId}/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'reject'])->name('rejectLeave');

// reject multiple leave -----------------------------------------------------------
Route::get('admin/rejectMultipleLeave', [App\Http\Controllers\LeaveApplicationController::class, 'rejectMultiple'])->name('rejectMultipleLeave');

// all employees' leave grade -----------------------------------------------------------
Route::get('employeesLeave/all', [App\Http\Controllers\LeaveGradeController::class, 'showAllEmployeesLeaveGrade'])->name('allEmployeesLeaveGrade');

// an employee's leave grade -----------------------------------------------------------
Route::get('employeesLeave/{id}',[App\Http\Controllers\EmployeeLeaveController::class, 'showAnEmployeesLeave'])->name('employeesLeaveGrade');

// all admins' leave grade -----------------------------------------------------------
Route::get('adminsLeave/all', [App\Http\Controllers\LeaveGradeController::class, 'showAllAdminsLeaveGrade'])->name('allAdminsLeaveGrade');

// an administrator's leave grade -----------------------------------------------------------
Route::get('adminsLeave/{id}',[App\Http\Controllers\AdminLeaveController::class, 'showAnAdminsLeave'])->name('adminsLeaveGrade');

// assign employee's leave grade -----------------------------------------------------------
Route::get('setEmployeesLeaveGrade/{id}',[App\Http\Controllers\LeaveGradeController::class, 'setEmployeesLeaveGradePage'])->name('setEmployeesLeaveGrade');

Route::post('setEmployeesLeaveGrade/update',[App\Http\Controllers\LeaveGradeController::class, 'updateEmployeesLeaveGrade'])->name('updateEmployeesLeaveGrade');

// assign admin's leave grade -----------------------------------------------------------
Route::get('setAdminsLeaveGrade/{id}',[App\Http\Controllers\LeaveGradeController::class, 'setAdminsLeaveGradePage'])->name('setAdminsLeaveGrade');
Route::post('setAdminsLeaveGrade/update',[App\Http\Controllers\LeaveGradeController::class, 'updateAdminsLeaveGrade'])->name('updateAdminsLeaveGrade');

// create new employees leave record every year (manually) -----------------------------------------------------------
Route::get('employeesLeave/createLeaveRecord',[App\Http\Controllers\EmployeeLeaveController::class, 'createLeaveRecord'])->name('createLeaveRecord');

// apply leave (administrator)-----------------------------------------------------------
Route::get('admin/applyLeave', [App\Http\Controllers\AdminLeaveApplicationController::class, 'showApplyLeavePage'])->name('showAdminApplyLeavePage');
Route::post('admin/applyLeave/submit', [App\Http\Controllers\AdminLeaveApplicationController::class, 'submitApplication'])->name('submitAdminApplication');

// cancel leave application -----------------------------------------------------------
Route::get('admin/cancelLeave/{adminId}/{id}', [App\Http\Controllers\AdminLeaveApplicationController::class, 'cancel'])->name('cancelLeaveAdmin');

// administrators' leave application list -----------------------------------------------------------
Route::get('admin/adminLeaveApplicationList', [App\Http\Controllers\AdminLeaveApplicationController::class, 'showAdminLeaveApplicationList'])->name('showAdminLeaveApplicationList');

// administrator own leave application list -----------------------------------------------------------
Route::get('admin/ownLeaveApplicationList', [App\Http\Controllers\AdminLeaveApplicationController::class, 'showOwnLeaveApplicationList'])->name('showAdminOwnLeaveApplicationList');

// approve administrators' leave -----------------------------------------------------------
Route::get('admin/approveAdminLeave/{adminId}/{id}', [App\Http\Controllers\AdminLeaveApplicationController::class, 'approve'])->name('approveAdminLeave');

// approve multiple administrators' leave -----------------------------------------------------------
Route::get('admin/approveMultipleAdminLeave', [App\Http\Controllers\AdminLeaveApplicationController::class, 'approveMultiple'])->name('approveMultipleAdminLeave');

// reject administrators' leave -----------------------------------------------------------
Route::get('admin/rejectAdminLeave/{adminId}/{id}', [App\Http\Controllers\AdminLeaveApplicationController::class, 'reject'])->name('rejectAdminLeave');

// reject multiple administrators' leave -----------------------------------------------------------
Route::get('admin/rejectMultipleAdminLeave', [App\Http\Controllers\AdminLeaveApplicationController::class, 'rejectMultiple'])->name('rejectMultipleAdminLeave');

// view own leave grade -----------------------------------------------------------
Route::get('admin/ownLeaveGrade',[App\Http\Controllers\AdminLeaveController::class, 'showAdminOwnLeave'])->name('adminOwnLeaveGrade');


//+++++++++++++++++++++++++++++++++++++++++++++++ ROUTE FOR EMPLOYEE +++++++++++++++++++++++++++++++++++++++++++++++

// apply leave -----------------------------------------------------------
Route::get('applyLeave', [App\Http\Controllers\LeaveApplicationController::class, 'showApplyLeavePage'])->name('showApplyLeavePage');
Route::post('applyLeave/submit', [App\Http\Controllers\LeaveApplicationController::class, 'submitApplication'])->name('submitApplication');

// own leave application list -----------------------------------------------------------
Route::get('leaveApplicationList', [App\Http\Controllers\LeaveApplicationController::class, 'showLeaveApplicationList'])->name('showLeaveApplicationList');

// cancel leave -----------------------------------------------------------
Route::get('leaveApplication/cancel/{employeeId}/{id}', [App\Http\Controllers\LeaveApplicationController::class, 'cancel'])->name('cancelLeave');

// cancel multiple leave -----------------------------------------------------------
Route::get('cancelMultipleLeave', [App\Http\Controllers\LeaveApplicationController::class, 'cancelMultiple'])->name('cancelMultiple');

// view own leave grade -----------------------------------------------------------
Route::get('employeesLeave',[App\Http\Controllers\EmployeeLeaveController::class, 'showEmployeeOwnLeave'])->name('employeeOwnLeaveGrade');


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++PAYROLL MANAGEMENT++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++ROUTE FOR ADMINISTRATORS++++++++++++++++++++++++++++++++++++++++++++

// show category of salary component -----------------------------------------------------------
Route::get('categoryOfSalaryComponent',[App\Http\Controllers\CategoryOfSalaryComponentController::class, 'show'])->name('showCategoryOfSalaryComponent');

// create salary component -----------------------------------------------------------
Route::get('createSalaryComponent',[App\Http\Controllers\SalaryComponentController::class, 'showCreateSalaryComponent'])->name('showCreateSalaryComponent');

Route::post('createSalaryComponent/store', [App\Http\Controllers\SalaryComponentController::class, 'createSalaryComponent'])->name('addSalaryComponent');

// show salary component -----------------------------------------------------------
Route::get('salaryComponent',[App\Http\Controllers\SalaryComponentController::class, 'showSalaryComponent'])->name('showSalaryComponent');

// edit salary component -----------------------------------------------------------
Route::get('editSalaryComponent/{id}',[App\Http\Controllers\SalaryComponentController::class, 'edit'])->name('editSalaryComponent');

Route::post('editSalaryComponent/update', [App\Http\Controllers\SalaryComponentController::class, 'update'])->name('updateSalaryComponent');

// delete salary component -----------------------------------------------------------
Route::get('salaryComponent/delete/{id}', [App\Http\Controllers\SalaryComponentController::class, 'delete'])->name('deleteSalaryComponent');

// create salary structure -----------------------------------------------------------
Route::get('createSalaryStructure', function() {
   return view('admin/payroll/createSalaryStructure');
})->name('createSalaryStructure');

Route::post('createSalaryStructure/store', [App\Http\Controllers\SalaryStructureController::class, 'createSalaryStructure'])->name('addSalaryStructure');

// show salary component for all job title -----------------------------------------------------------
Route::get('salaryComponentForAllJobTitle',[App\Http\Controllers\TitleComponentController::class, 'showSalaryComponentForAllJobTitle'])->name('showSalaryComponentForAllJobTitle');

// show salary component for a job title -----------------------------------------------------------
Route::get('salaryComponentForAJobTitle/{id}',[App\Http\Controllers\TitleComponentController::class, 'showSalaryComponentForAJobTitle'])->name('showSalaryComponentForAJobTitle');

// add salary component for job title -----------------------------------------------------------
Route::post('addSalaryComponentForJobTitle', [App\Http\Controllers\TitleComponentController::class, 'addTitleComponent'])->name('addSalaryComponentForJobTitle');

// edit salary component for job title -----------------------------------------------------------
Route::get('editSalaryComponentForJobTitle/{id}',[App\Http\Controllers\TitleComponentController::class, 'showEdit'])->name('showEditSalaryComponentForJobTitle');

Route::post('editSalaryComponentForJobTitle/update', [App\Http\Controllers\TitleComponentController::class, 'editTitleComponent'])->name('editSalaryComponentForJobTitle');

// delete salary component for job title -----------------------------------------------------------
Route::get('deleteSalaryComponentForJobTitle/{id}', [App\Http\Controllers\TitleComponentController::class, 'deleteTitleComponent'])->name('deleteSalaryComponentForJobTitle');

// show payroll page -----------------------------------------------------------
Route::get('payroll',[App\Http\Controllers\PayrollController::class, 'showPayrollPage'])->name('showPayrollPage');

// show employee payroll item page -----------------------------------------------------------
Route::get('payroll/{id}',[App\Http\Controllers\PayrollController::class, 'showEditPayrollItemPage'])->name('showEditPayrollItemPage');


// show payroll history page -----------------------------------------------------------

// show employee payroll history page -----------------------------------------------------------


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
