<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\LeaveApplication;
use App\Models\Employee;
use Session;

class LeaveApplicationController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

   public function show() {
      $employees=Employee::all()->where('id',Auth::id());
      return view('applyLeave.blade.php')->with('employees',$employees);
   }
}
