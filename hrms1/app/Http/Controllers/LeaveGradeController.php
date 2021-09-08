<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveGrade;
use App\Models\Employee;
use App\Models\EmployeesLeave;
use Session;

class LeaveGradeController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

    public function store() {
      $r=request();
      $addLeaveGrade=LeaveGrade::create([
         'name'=>$r->name,
      ]);

      Session::flash('success',"Leave grade created successfully!");
      return redirect()->route('showLeaveGrades');
   }

   public function show() {
      $leaveGrades=LeaveGrade::all();
      return view('showLeaveGrades')->with('leaveGrades',$leaveGrades);
   }

   public function edit($id) {
      $leaveGrades=LeaveGrade::all()->where('id',$id);
      return view('editLeaveGradeName')->with('leaveGrades',$leaveGrades);
   }

   public function update() {
      $r=request();
      $leaveGrades=LeaveGrade::find($r->id);

      $leaveGrades->name=$r->name;
      $leaveGrades->save();

      Session::flash('success',"Leave grade updated successfully!");
      return redirect()->route('showLeaveGrades');
   }

   public function delete($id) {
      $leaveGrades=LeaveGrade::find($id);
      $leaveGrades->delete();
      return redirect()->route('showLeaveGrades');
   }

   public function showAssignLeaveGradePage($id) {
      $employees=Employee::all()->where('id',$id);
      $leaveGrades=LeaveGrade::all();

      return view('assignLeaveGradePage')->with('employees',$employees)
                                          ->with('leaveGrades',$leaveGrades);
   }

   public function assignLeaveGrade($id) {
      $employees=Employee::all()->where('id',$id);
      $leaveGrades=DB::table('leave_grades')
                  ->orderBy('name','asc')
                  ->get();
      return view('assignLeaveGrade')->with('employees',$employees)
                                    ->with('leaveGrades',$leaveGrades);
   }

   public function showAllEmployeesLeaveGrade() {
      $employees=DB::table('employees')
                  ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
                  ->select('leave_grades.name as leaveGradeName', 'employees.*')
                  ->orderBy('id','asc')
                  ->get();

      return view('allEmployeesLeaveGrade')->with('employees',$employees);
   }

   public function showAnEmployeesLeave($id) {
      $employees=DB::table('employees')
                  ->leftjoin('leave_grades','leave_grades.id','=','employees.leave_grade')
                  ->select('leave_grades.name as leaveGradeName', 'employees.*')
                  ->where('employees.id','=',$id)
                  ->get();

      return view('employeesLeaveGrade')->with('employees',$employees);
   }
}
