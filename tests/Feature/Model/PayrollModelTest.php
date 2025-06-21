<?php

namespace Tests\Feature\Model;

use App\Models\Company;
use App\Models\Payroll;
use Carbon\Carbon;
use Database\Seeders\CompanySeeder;
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

class PayrollModelTest extends TestCase
{
    public function testHasCompany()
    {
        $this->seed([CompanySeeder::class, PayrollSeeder::class]);

        $payroll = Payroll::first();
        self::assertNotNull($payroll);

        $company = $payroll->company;
        self::assertEquals("samplecompany1@example.com", $company->email);
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

        $payroll = Payroll::first();
        self::assertNotNull($payroll);

        $salaries = $payroll->salaries;
        self::assertCount(1, $salaries);
        self::assertEquals(2000, $salaries[0]->gross_salary);
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

        $payroll = Payroll::first();
        self::assertNotNull($payroll);

        $payments = $payroll->payments;
        self::assertCount(1, $payments);
    }

    public function testGetFormatDate()
    {
        $this->seed([
            CompanySeeder::class,
            PayrollSeeder::class
        ]);

        $payroll = Payroll::first();
        self::assertNotNull($payroll);

        $date = $payroll->getMonthYearAttribute();
        self::assertEquals(Carbon::now()->year . '-' . Carbon::now()->month, $date);

        $date = $payroll->getMonthStringAttribute();
        self::assertEquals(Carbon::now()->format('F') . ' ' . Carbon::now()->format('Y'), $date);
    }
}
