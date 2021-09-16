<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryOfSalaryComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('category_of_salary_components')->insert([
          'name' => 'Allowances',
      ]);

      DB::table('category_of_salary_components')->insert([
          'name' => 'Other Perquisites',
      ]);

      DB::table('category_of_salary_components')->insert([
          'name' => 'Remuneration',
      ]);

      DB::table('category_of_salary_components')->insert([
          'name' => 'Deductions',
      ]);

      DB::table('category_of_salary_components')->insert([
          'name' => 'Benefits-in-Kind',
      ]);
    }
}
