<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\SalaryStructure;
use App\Models\SalaryComponent;
use App\Models\StructureComponent;
use Session;

class SalaryStructureController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   //+++++++++++++ CREATE SALARY STRUCTURE +++++++++++++++++++
   public function createSalaryStructure() {
      $r=request();
      $addSalaryStructure=SalaryStructure::create([
         'name'=>$r->name,
         'status'=>'Added',
      ]);

      Session::flash('success',"Salary structure created successfully!");
      return redirect()->route('showSalaryStructure');
   }

   //+++++++++++++ SHOW SALARY STRUCTURE +++++++++++++++++++
   public function showSalaryStructure() {
      $salaryStructures=SalaryStructure::all();
      $structureComponents=DB::table('structure__components')
      ->leftjoin('salary_components','salary_components.id','structure__components.salary_component')
      ->select('salary_components.name as salaryComponentName','structure__components.*')
      ->where('salary_components.status','!=','Deleted')
      ->orderBy('salary_components.name','asc')
      ->get();

      return view('admin/payroll/showSalaryStructure')
      ->with('salaryStructures',$salaryStructures)
      ->with('structureComponents',$structureComponents);
   }
}
