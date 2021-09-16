<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\EmployeePayroll;
use App\Models\EmployeeSalary;
use App\Models\CategoryOfSalaryComponent;
use App\Models\SalaryComponent;
use Carbon\Carbon;

class PayrollController extends Controller
{
   public function __construct() {
      $this->middleware('auth');
   }

   public function showPayrollPage() {
      $employees=DB::table('employees')
      ->where('status','!=','INACTIVE')
      ->get();

      $employeeSalaries=EmployeeSalary::all();

      $employeePayrolls=DB::table('employee_payrolls')
      ->where('month','=',Carbon::now()->format('m'))
      ->where('year','=',Carbon::now()->format('Y'))
      ->get();

      $isExist=0;

      if($employeePayrolls->isNotEmpty()) {
         $isExist=1;
      } else {
         foreach($employees as $employee) {
            $employeeId=$employee->id;
            $employee=Employee::find($employeeId);

            foreach($employeeSalaries as $employeeSalary) {
               $employeeSalaryId=$employeeSalary->id;
               $employeeSalary=EmployeeSalary::find($employeeSalaryId);

               $createEmployeePayrolls=EmployeePayroll::create([
                  'employee'=>$employee->id,
                  'component'=>$employeeSalary->component,
                  'amount'=>$employeeSalary->amount,
                  'is_additional'=>false,
                  'month'=>Carbon::now()->format('m'),
                  'year'=>Carbon::now()->format('Y'),
               ]);
            }
         }
      }

      return view('admin/payroll/runPayrollPage')
      ->with('employees',$employees)
      ->with('employeeSalaries',$employeeSalaries);
   }

   public function showEditPayrollItemPage($id) {
      $employees=DB::table('employees')
      ->where('id','=',$id)
      ->get();

      $categoriesOfSalaryComponent=CategoryOfSalaryComponent::all();
      $salaryComponents=DB::table('salary_components')
      ->where('status','!=','Deleted')
      ->get();

      $employeePayrolls=DB::table('employee_payrolls')
      ->leftjoin('salary_components','salary_components.id','employee_payrolls.component')
      ->select('salary_components.name as salaryComponentName','employee_payrolls.*')
      ->where('employee','=',$id)
      ->where('month','=',Carbon::now()->format('m'))
      ->where('year','=',Carbon::now()->format('Y'))
      ->get();

      return view('admin/payroll/editPayrollItem')
      ->with('employees',$employees)
      ->with('categoriesOfSalaryComponent',$categoriesOfSalaryComponent)
      ->with('salaryComponents',$salaryComponents);
   }
}
