<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\TitleComponent;
use App\Models\SalaryComponent;
use App\Models\CategoryOfSalaryComponent;
use App\Models\JobTitle;

class TitleComponentController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   public function showSalaryComponentForAllJobTitle() {
      $jobTitles=JobTitle::all();
      $titleComponents=DB::table('title_components')
      ->leftjoin('salary_components','salary_components.id','title_components.salary_component')
      ->select('salary_components.name as salaryComponentName','title_components.*')
      ->get();

      return view('admin/payroll/salaryComponentForAllJobTitle')
      ->with('jobTitles',$jobTitles)
      ->with('titleComponents',$titleComponents);
   }

   public function showSalaryComponentForAJobTitle($id) {
      $jobTitles=JobTitle::all()->where('id',$id);

      $currentTitleComponents=DB::table('title_components')
      ->leftjoin('salary_components','salary_components.id','title_components.salary_component')
      ->select('salary_components.name as salaryComponentName','title_components.*')
      ->where('title_components.job_title','=',$id)
      ->orderBy('salary_components.id','asc')
      ->get();

      $categoriesOfSalaryComponent=CategoryOfSalaryComponent::all();

      $salaryComponents=DB::table('salary_components')
      ->where('status','!=','Deleted')
      ->orderBy('name','asc')
      ->get();

      return view('admin/payroll/salaryComponentForAJobTitle')
      ->with('jobTitles',$jobTitles)
      ->with('currentTitleComponents',$currentTitleComponents)
      ->with('categoriesOfSalaryComponent',$categoriesOfSalaryComponent)
      ->with('salaryComponents',$salaryComponents);
   }
}
