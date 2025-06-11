<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use Database\Seeders\CompanySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentModelTest extends TestCase
{
    public function testBelongsToCompany()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class]);

        $department = Department::where("name", "Finance")->first();
        self::assertNotNull($department);

        $company = $department->company;
        self::assertEquals("samplecompany1@example.com", $company->email);
        self::assertEquals("Sample Company 1", $company->name);
    }

    public function testHasManyDesignations()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class]);

        $department = Department::where("name", "Finance")->first();
        self::assertNotNull($department);
        $designations = $department->designations;
        self::assertCount(2, $designations);
        self::assertEquals("Auditor", $designations[0]->name);
        self::assertEquals("Accountant", $designations[1]->name);
    }

    public function testHasManyEmployees()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class, EmployeeSeeder::class]);

        $department = Department::where("name", "Finance")->first();
        self::assertNotNull($department);

        $employees = $department->employees;
        self::assertCount(2, $employees);
        self::assertEquals("Employee 1", $employees[0]->name);
        self::assertEquals("employee1@example.com", $employees[0]->email);
        self::assertEquals("Employee 2", $employees[1]->name);
        self::assertEquals("employee2@example.com", $employees[1]->email);
    }

    public function testScopeInCompany()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class]);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);

        session(["company_id" => $company->id]);

        $departments = Department::query()->inCompany()->get();
        self::assertCount(2, $departments);
        self::assertEquals("Information Technology (IT)", $departments[0]->name);
        self::assertEquals("Finance", $departments[1]->name);

        $company = Company::where("email", "samplecompany2@example.com")->first();

        session(["company_id" => $company->id]);
        self::assertNotNull($company);

        $departments = Department::query()->inCompany()->get();
        self::assertCount(0, $departments);

        session()->flush();
    }
}
