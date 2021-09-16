<?php

namespace App\Http\Controllers;
use DB;
use App\Models\CategoryOfSalaryComponent;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Session;

class CategoryOfSalaryComponentController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   //+++++++++++++ SHOW CATEGORY OF SALARY COMPONENT +++++++++++++++++++
   public function show() {
      $salaryComponents=DB::table('salary_components')
      ->where('salary_components.status','!=','Deleted')
      ->orderBy('salary_components.name','asc')
      ->get();

      return view('admin/payroll/showCategoryOfSalaryComponent')
      ->with('categoriesOfSalaryComponent',CategoryOfSalaryComponent::all())
      ->with('salaryComponents',$salaryComponents);
   }
}
