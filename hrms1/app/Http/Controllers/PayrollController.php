<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Employee;
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
      ->leftjoin('job_titles','job_titles.id','employees.id')
      ->select('job_titles.job_title_name as jobTitleName','employees.*')
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

   public function showPayrollItemPage($id) {
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

      return view('admin/payroll/showEmployeePayrollItem')
      ->with('employees',$employees)
      ->with('categoriesOfSalaryComponent',$categoriesOfSalaryComponent)
      ->with('salaryComponents',$salaryComponents)
      ->with('employeePayrolls',$employeePayrolls);
   }

   public function addPayrollItem() {
      $r=request();

      $addPayrollItem=EmployeePayroll::create([
         'employee'=>$r->id,
         'component'=>$r->salaryComponent,
         'amount'=>$r->amount,
         'is_additional'=>true,
         'month'=>Carbon::now()->format('m'),
         'year'=>Carbon::now()->format('Y'),
      ]);

      Session::flash('success',"Add payroll item successfully!");
      return redirect()->route('showEmployeePayrollPage',['id'=>$r->id]);
   }

   public function showEditEmployeePayroll($id) {
      $employeePayrolls=DB::table('employee_payrolls')
      ->leftjoin('employees','employees.id','employee_payrolls.employee')
      ->leftjoin('salary_components','salary_components.id','employee_payrolls.component')
      ->leftjoin('category_of_salary_components','category_of_salary_components.id','salary_components.category')
      ->select('employees.full_name as employeeName','employee_payrolls.*','salary_components.name as salaryComponentName','salary_components.category as salaryComponentCategory','category_of_salary_components.name as category')
      ->where('employee_payrolls.id','=',$id)
      ->get();

      $categoriesOfSalaryComponent=CategoryOfSalaryComponent::all();
      $salaryComponents=DB::table('salary_components')
      ->where('status','!=','Deleted')
      ->get();

      return view('admin/payroll/editEmployeePayroll')
      ->with('categoriesOfSalaryComponent',$categoriesOfSalaryComponent)
      ->with('salaryComponents',$salaryComponents)
      ->with('employeePayrolls',$employeePayrolls);
   }

   public function editEmployeePayroll() {
      $r=request();

      $employeePayroll=EmployeePayroll::find($r->id);
      $employeeId=$employeePayroll->employee;
      $employeePayroll->component=$r->component;
      $employeePayroll->amount=$r->amount;
      $employeePayroll->save();

      Session::flash('success',"Add payroll item successfully!");
      return redirect()->route('showEmployeePayrollPage',['id'=>$employeeId]);
   }

   public function deleteEmployeePayroll($id) {
      $r=request();

      $employeePayroll=EmployeePayroll::find($id);
      $employeeId=$employeePayroll->employee;
      $employeePayroll->delete();

      Session::flash('success',"Delete payroll item successfully!");
      return redirect()->route('showEmployeePayrollPage',['id'=>$employeeId]);

   }
}
