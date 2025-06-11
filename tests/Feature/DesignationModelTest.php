<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Designation;
use Database\Seeders\CompanySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DesignationModelTest extends TestCase
{
    public function testBelongsToDepartment()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class]);

        $designation = Designation::where("name", "Auditor")->first();
        self::assertNotNull($designation);

        $department = $designation->department;
        self::assertNotNull($department);
        self::assertEquals("Finance", $department->name);
    }

    public function testHasManyEmployees()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class, EmployeeSeeder::class]);

        $designation = Designation::where("name", "Auditor")->first();
        self::assertNotNull($designation);

        $employees = $designation->employees;
        self::assertCount(2, $employees);
        self::assertEquals("employee1@example.com", $employees[0]->email);
        self::assertEquals("employee2@example.com", $employees[1]->email);
    }

    public function testScopeInCompany()
    {
        $this->seed([CompanySeeder::class, DepartmentSeeder::class, DesignationSeeder::class]);

        $company = Company::where("email", "samplecompany1@example.com")->first();
        self::assertNotNull($company);

        session(["company_id" => $company->id]);

        $designations = Designation::query()->inCompany()->get();
        self::assertCount(4, $designations);

        session()->flush();
    }
}
