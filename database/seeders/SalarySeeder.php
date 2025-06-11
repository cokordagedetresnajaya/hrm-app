<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::where("email", "employee1@example.com")->first();
        $payroll = Payroll::first();

        $salary = Salary::create([
            "payroll_id" => $payroll->id,
            "employee_id" => $employee->id,
            "gross_salary" => 2000
        ]);
    }
}
