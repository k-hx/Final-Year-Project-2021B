<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

   public function show() {
     $employees=Employee::all();
     return view('showEmployees')->with('employees',$employees);
  }
}
