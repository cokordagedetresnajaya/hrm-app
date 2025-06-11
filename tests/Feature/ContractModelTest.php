<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Employee;
use Carbon\Carbon;
use Database\Seeders\CompanySeeder;
use Database\Seeders\ContractSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ContractModelTest extends TestCase
{
    public function testHasEmployee()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class
        ]);

        $contract = Contract::first();
        self::assertNotNull($contract);

        $employee = $contract->employee;
        self::assertEquals("employee1@example.com", $employee->email);
    }

    public function testHasDesignation()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class
        ]);

        $contract = Contract::first();
        self::assertNotNull($contract);

        $designation = $contract->designation;
        self::assertEquals("Auditor", $designation->name);
    }

    public function testScopeInCompany()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class
        ]);

        $company = Company::where('email', 'samplecompany1@example.com')->first();
        self::assertNotNull($company);

        session(['company_id' => $company->id]);

        $contracts = Contract::query()->inCompany()->get();
        self::assertCount(1, $contracts);

        $company = Company::where('email', 'samplecompany2@example.com')->first();
        self::assertNotNull($company);

        session(['company_id' => $company->id]);

        $contracts = Contract::query()->inCompany()->get();
        self::assertCount(0, $contracts);

        session()->flush();
    }

    public function testScopeBySearchEmployee()
    {
        $this->seed([
            CompanySeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class
        ]);

        $contracts = Contract::query()->searchByEmployee("1")->get();
        self::assertCount(1, $contracts);

        $contracts = Contract::query()->searchByEmployee("2")->get();
        self::assertCount(0, $contracts);
    }
}
