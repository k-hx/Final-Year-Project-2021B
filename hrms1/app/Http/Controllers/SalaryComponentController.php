<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryComponent;
use App\Models\CategoryOfSalaryComponent;
use DB;
use Session;

class SalaryComponentController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   //+++++++++++++ SHOW THE PAGE FOR CREATING SALARY COMPONENT +++++++++++++++++++
   public function showCreateSalaryComponent() {
      return view('admin/payroll/createSalaryComponent')
      ->with('categoriesOfSalaryComponent',CategoryOfSalaryComponent::all());
   }

   //+++++++++++++ CREATE SALARY COMPONENT +++++++++++++++++++
   public function createSalaryComponent() {
      $r=request();
      $addSalaryComponent=SalaryComponent::create([
         'name'=>$r->name,
         'category'=>$r->category,
         'status'=>'Added',
      ]);

      Session::flash('success',"Salary component created successfully!");
      return redirect()->route('showSalaryComponent');
   }

   //+++++++++++++ CREATE SALARY COMPONENT +++++++++++++++++++
   public function showSalaryComponent() {
      $salaryComponents=DB::table('salary_components')
      ->leftjoin('category_of_salary_components','category_of_salary_components.id','salary_components.category')
      ->select('category_of_salary_components.name as categoryName','salary_components.*')
      ->where('salary_components.status','!=','Deleted')
      ->orderBy('salary_components.id','asc')
      ->get();

      return view('admin/payroll/showSalaryComponent')
      ->with('salaryComponents',$salaryComponents);
   }

   //+++++++++++++ SHOW EDIT SALARY COMPONENT PAGE +++++++++++++++++++
   public function edit($id) {
      $salaryComponents=SalaryComponent::all()->where('id',$id);
      $categoriesOfSalaryComponent=CategoryOfSalaryComponent::all();

      return view('admin/payroll/editSalaryComponent')
      ->with('salaryComponents',$salaryComponents)
      ->with('categoriesOfSalaryComponent',$categoriesOfSalaryComponent);
   }

   //+++++++++++++ EDIT SALARY COMPONENT +++++++++++++++++++
   public function update() {
      $r=request();
      $salaryComponents=SalaryComponent::find($r->id);

      $salaryComponents->name=$r->name;
      $salaryComponents->category=$r->categoryOfSalaryComponent;
      $salaryComponents->status='Edited';
      $salaryComponents->save();

      Session::flash('success',"Salary component updated successfully!");
      return redirect()->route('showSalaryComponent');
   }

   //+++++++++++++ DELETE SALARY COMPONENT +++++++++++++++++++
   public function delete($id) {
      $salaryComponents=SalaryComponent::find($id);
      $salaryComponents->status='Deleted';
      $salaryComponents->save();

      Session::flash('success',"Salary component deleted successfully!");
      return redirect()->route('showSalaryComponent');
   }

}
