<?php

namespace Tests\Feature\Model;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\CompanySeeder;
use Database\Seeders\ContractSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\PayrollSeeder;
use Database\Seeders\SalarySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EmployeeModelTest extends TestCase
{
    public function testBelongsToDesignation()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class, EmployeeSeeder::class]);

        $employee = Employee::where("email", "employee1@example.com")->first();
        self::assertNotNull($employee);

        $designation = $employee->designation;
        self::assertEquals("Auditor", $designation->name);
    }

    public function testHasDepartment()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class, EmployeeSeeder::class]);

        $employee = Employee::where("email", "employee1@example.com")->first();
        self::assertNotNull($employee);

        $department = $employee->department();
        self::assertEquals("Finance", $department->name);
    }

    public function testHasManyContracts()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class
        ]);

        $employee = Employee::where("email", "employee1@example.com")->first();
        self::assertNotNull($employee);

        $contracts = $employee->contracts;
        self::assertCount(1, $contracts);
    }

    public function testScopeInCompany()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class, EmployeeSeeder::class]);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);

        session(["company_id" => $company->id]);

        $employees = Employee::query()->inCompany()->get();
        self::assertCount(2, $employees);
        self::assertEquals("Employee 1", $employees[0]->name);
        self::assertEquals("Employee 2", $employees[1]->name);

        // Test with company without employees
        $company = Company::where("email", "samplecompany2@example.com")->first();
        self::assertNotNull($company);

        session(["company_id" => $company->id]);

        $employees = Employee::query()->inCompany()->get();
        self::assertCount(0, $employees);
    }

    public function testScopeSearchByName()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class, EmployeeSeeder::class]);

        $employees = Employee::searchByName("Employee")->get();
        self::assertCount(2, $employees);

        $employees = Employee::searchByName("1")->get();
        self::assertCount(1, $employees);

        $employees = Employee::searchByName("Not match")->get();
        self::assertCount(0, $employees);
    }

    public function testHasManySalaries()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            PayrollSeeder::class,
            SalarySeeder::class
        ]);

        $employee = Employee::where("email", "employee1@example.com")->first();
        self::assertNotNull($employee);

        $salaries = $employee->salaries;
        self::assertCount(1, $salaries);
        self::assertEquals(2000, $salaries[0]->gross_salary);

        $employee = Employee::where("email", "employee2@example.com")->first();
        self::assertNotNull($employee);

        $salaries = $employee->salaries;
        self::assertCount(0, $salaries);
    }

    public function testHasManyPayments()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            PayrollSeeder::class,
            PaymentSeeder::class
        ]);

        $employee = Employee::where("email", "employee1@example.com")->first();
        self::assertNotNull($employee);

        $payments = $employee->payments;
        self::assertCount(1, $payments);

        $employee = Employee::where("email", "employee2@example.com")->first();
        self::assertNotNull($employee);

        $payments = $employee->payments;
        self::assertCount(0, $payments);
    }

    public function testGetActiveContract()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class
        ]);

        $employee = Employee::where('email', 'employee1@example.com')->firstOrFail();

        $start = now()->format('Y-m-d');
        $end = now()->addDays(7)->format('Y-m-d');

        $contract = $employee->getActiveContract($start, $end);

        $this->assertNotNull($contract);
        $this->assertTrue(
            $contract->start_date >= $start && $contract->end_date <= $end
        );

        $start = now()->subDays(10)->format('Y-m-d');
        $end = now()->subDays(7)->format('Y-m-d');

        $contract = $employee->getActiveContract($start, $end);

        $this->assertNull($contract);
    }
}
