<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryComponent;
use DB;
use Session;

class SalaryComponentController extends Controller
{
   public function __construct() {
        $this->middleware('auth');
   }

   //+++++++++++++ CREATE SALARY COMPONENT +++++++++++++++++++
   public function store() {
      $r=request();
      $addSalaryComponent=SalaryComponent::create([
         'name'=>$r->name,
         'category'=>$r->category,
      ]);

      Session::flash('success',"Salary component created successfully!");
      return redirect()->route('showSalaryComponents');
   }

}
