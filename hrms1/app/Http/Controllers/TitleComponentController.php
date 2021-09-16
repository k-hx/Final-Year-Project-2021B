<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\TitleComponent;
use App\Models\SalaryComponent;
use App\Models\CategoryOfSalaryComponent;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Admin;
use App\Models\AdminSalary;
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

   public function addTitleComponent() {
      $r=request();

      $amount=$r->amount;
      $salaryComponent=SalaryComponent::find($r->salaryComponent);
      if($salaryComponent->category = 4) {
         $amount= -($amount);
      }

      //create in title_components table
      $addTitleComponent=TitleComponent::create([
         'job_title'=>$r->jobTitleId,
         'salary_component'=>$r->salaryComponent,
         'amount'=>$amount,
      ]);

      //employee salary ------------------------------------
      //find the employee with the job title
      $employees=DB::table('employees')
      ->where('job_title','=',$r->jobTitleId)
      ->where('status','!=','INACTIVE')
      ->get();

      foreach($employees as $employee) {
         $employeeId=$employee->id;
         $employee=Employee::find($employeeId);

         //find the related employee salary record
         $employeeSalaries=DB::table('employee_salaries')
         ->where('employee','=',$employee->id)
         ->where('component','=',$r->salaryComponent)
         ->get();

         $number=0;
         //update employee salary record
         foreach($employeeSalaries as $employeeSalary) {
            $number=$number+1;

            $employeeSalaryId=$employeeSalary->id;
            $employeeSalary=EmployeeSalary::find($employeeSalaryId);

            $employeeSalary->amount=$amount;
            $employeeSalary->save();
         }

         //if no record existed before, create it
         if($number===0) {
            $createEmployeeSalary=EmployeeSalary::create([
               'employee'=>$employeeId,
               'component'=>$r->salaryComponent,
               'amount'=>$amount,
            ]);
         }
      }

      //admin salary ------------------------------------
      //find the administrators with the job title
      $admins=DB::table('admins')
      ->where('job_title','=',$r->jobTitleId)
      ->where('status','!=','INACTIVE')
      ->get();

      foreach($admins as $admin) {
         $adminId=$admin->id;
         $admin=Admin::find($adminId);

         //find the related admin salary record
         $adminSalaries=DB::table('admin_salaries')
         ->where('admin','=',$adminId)
         ->where('salary_component','=',$salaryComponent)
         ->get();

         $number=0;
         //update employee salary record
         foreach($adminSalaries as $adminSalary) {
            $number=$number+1;

            $adminSalaryId=$adminSalary->id;
            $adminSalary=AdminSalary::find($adminSalaryId);

            $adminSalary->amount=$amount;
            $adminSalary->save();
         }

         //if no record existed before, create it
         if($number===0) {
            $createAdminSalary=AdminSalary::create([
               'admin'=>$adminId,
               'component'=>$r->salaryComponent,
               'amount'=>$amount,
            ]);
         }
      }

      Session::flash('success',"Salary component added successfully!");
      return redirect()->route('showSalaryComponentForAJobTitle', ['id' => $r->jobTitleId]);
   }

   public function showEdit($id) {
      $titleComponents=DB::table('title_components')
      ->leftjoin('salary_components','salary_components.id','title_components.salary_component')
      ->select('salary_components.name as salaryComponentName','title_components.*')
      ->where('title_components.id','=',$id)
      ->get();

      foreach($titleComponents as $titleComponent) {
         $titleComponentId=$titleComponent->id;
         $titleComponent=TitleComponent::find($titleComponentId);

         $currentTitleComponents=DB::table('title_components')
         ->where('job_title','=',$titleComponent->job_title)
         ->orderBy('salary_component','asc')
         ->get();

         $categoriesOfSalaryComponent=CategoryOfSalaryComponent::all();

         $currentSalaryComponents=DB::table('salary_components')
         ->where('id','=',$titleComponent->salary_component)
         ->get();
      }

      $salaryComponents=DB::table('salary_components')
      ->where('status','!=','Deleted')
      ->orderBy('id','asc')
      ->get();

      return view('admin/payroll/editSalaryComponentForJobTitle')
      // return view('debuggingView')
      ->with('titleComponents',$titleComponents)
      ->with('currentTitleComponents',$currentTitleComponents)
      ->with('categoriesOfSalaryComponent',$categoriesOfSalaryComponent)
      ->with('currentSalaryComponents',$currentSalaryComponents)
      ->with('salaryComponents',$salaryComponents);
   }

   public function editTitleComponent() {
      //update title component record
      $r=request();
      $titleComponents=TitleComponent::find($r->id);

      $titleComponents->salary_component=$r->salaryComponent;

      $amount=$r->amount;
      $salaryComponents=SalaryComponent::find($r->salaryComponent);
      if($salaryComponents->category = 4) {
         $amount=-($amount);
      }

      $titleComponents->amount=$amount;
      $titleComponents->save();

      //update employee salary ------------------------------------
      //find the employee with the job title
      $employees=DB::table('employees')
      ->where('job_title','=',$titleComponents->job_title)
      ->where('status','!=','INACTIVE')
      ->get();

      foreach($employees as $employee) {
         $employeeId=$employee->id;
         $employee=Employee::find($employeeId);

         //find the related employee salary record
         $employeeSalaries=DB::table('employee_salaries')
         ->where('employee',$employee->id)
         ->where('component','=',$r->salaryComponent)
         ->get();

         $number=0;
         //update employee salary record
         foreach($employeeSalaries as $employeeSalary) {
            $number=$number+1;

            $employeeSalaryId=$employeeSalary->id;
            $employeeSalary=EmployeeSalary::find($employeeSalaryId);

            $employeeSalary->amount=$amount;
            $employeeSalary->save();
         }

         //if no record existed before, create it
         if($number===0) {
            $createEmployeeSalary=EmployeeSalary::create([
               'employee'=>$employeeId,
               'component'=>$r->salaryComponent,
               'amount'=>$amount,
            ]);
         }
      }

      //update admin salary ------------------------------------
      //find the administrators with the job title
      $admins=DB::table('admins')
      ->where('job_title','=',$r->jobTitleId)
      ->where('status','!=','INACTIVE')
      ->get();

      foreach($admins as $admin) {
         $adminId=$admin->id;
         $admin=Admin::find($adminId);

         //find the related admin salary record
         $adminSalaries=DB::table('admin_salaries')
         ->where('admin','=',$adminId)
         ->where('salary_component','=',$r->salaryComponent)
         ->get();

         $number=0;
         //update employee salary record
         foreach($adminSalaries as $adminSalary) {
            $number=$number+1;

            $adminSalaryId=$adminSalary->id;
            $adminSalary=AdminSalary::find($adminSalaryId);

            $adminSalary->amount=$amount;
            $adminSalary->save();
         }

         //if no record existed before, create it
         if($number===0) {
            $createAdminSalary=AdminSalary::create([
               'admin'=>$adminId,
               'component'=>$r->salaryComponent,
               'amount'=>$amount,
            ]);
         }
      }

      Session::flash('success',"Details of salary component for the job title updated successfully!");
      return redirect()->route('showSalaryComponentForAJobTitle', ['id' => $titleComponents->job_title]);
   }

   public function deleteSalaryComponentForJobTitle($id) {
      $titleComponents=TitleComponent::find($id);

      // update employee salary record -------------------------------
      // find the employee with the job title
      $employees=DB::table('employees')
      ->where('job_title','=',$titleComponents->job_title)
      ->where('status','!=','INACTIVE')
      ->get();

      foreach($employees as $employee) {
         $employeeId=$employee->id;
         $employee=Employee::find($employeeId);

         //find the related employee salary record
         $employeeSalaries=DB::table('employee_salaries')
         ->where('employee','=',$employee->id)
         ->where('component','=',$titleComponents->salary_component)
         ->get();

         //update employee salary record
         foreach($employeeSalaries as $employeeSalary) {
            $employeeSalaryId=$employeeSalary->id;
            $employeeSalary=EmployeeSalary::find($employeeSalaryId);
            $employeeSalary->delete();
         }
      }

      // update admin salary record -------------------------------
      $admins=DB::table('admins')
      ->where('job_title','=',$r->jobTitleId)
      ->where('status','!=','INACTIVE')
      ->get();

      foreach($admins as $admin) {
         $adminId=$admin->id;
         $admin=Admin::find($adminId);

         //find the related admin salary record
         $adminSalaries=DB::table('admin_salaries')
         ->where('admin','=',$adminId)
         ->where('salary_component','=',$titleComponents->salary_component)
         ->get();

         // update employee salary record
         foreach($adminSalaries as $adminSalary) {
            $adminSalaryId=$adminSalary->id;
            $adminSalary=AdminSalary::find($adminSalaryId);
            $adminSalary->delete();
         }
      }

      Session::flash('success',"Salary component for the job title is deleted successfully!");
      return redirect()->route('showSalaryComponentForAJobTitle', ['id' => $title_components->jobTitleId]);
   }

   public function whenAssignOrChangeJobTitleEmployee() {
      $titleComponents=DB::table('title_components')
      ->where('job_title','=',$jobTitle)
      ->get();

      $employeeSalaries=DB::table('employee_salaries')
      ->where('employee','=',$employeeId)
      ->get();

      $isMatched=0;
      foreach($titleComponents as $titleComponent) {
         $isMatched=0;

         $titleComponents=TitleComponent::find($titleComponents->id);
         foreach($employeeSalaries as $employeeSalary) {
            $employeeSalary=EmployeeSalary::find($employeeSalary->id);

            if($employeeSalary->component == $titleComponent->salary_component) {
               $isMatched=1;

               $amount=$titleComponent->amount;
               $salaryComponent=SalaryComponent::find($titleComponent->salary_component);
               if($salaryComponent->category = 4) {
                  $amount=-($amount);
               }
               $employeeSalary->amount=$amount;
               $employeeSalary->save();
            }
         }

         if($isMatched == 0) {
            $createAdminSalary=AdminSalary::create([
               'admin'=>$adminId,
               'component'=>$r->salaryComponent,
               'amount'=>$amount,
            ]);
         }
      }
      
   }
}
