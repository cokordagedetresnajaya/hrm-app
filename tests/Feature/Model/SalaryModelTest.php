<?php

namespace Tests\Feature\Model;

use App\Models\Payroll;
use App\Models\Salary;
use Carbon\Carbon;
use Database\Seeders\CompanySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\PayrollSeeder;
use Database\Seeders\SalarySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalaryModelTest extends TestCase
{
    public function testHasPayroll()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            PayrollSeeder::class,
            SalarySeeder::class
        ]);

        $salary = Salary::first();
        self::assertNotNull($salary);

        $payroll = $salary->payroll;
        self::assertEquals(Carbon::now()->year, $payroll->year);
        self::assertEquals(Carbon::now()->month, $payroll->month);
    }

    public function testHasEmployee()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            PayrollSeeder::class,
            SalarySeeder::class
        ]);

        $salary = Salary::first();
        self::assertNotNull($salary);

        $employee = $salary->employee;
        self::assertEquals("employee1@example.com", $employee->email);
    }
}
