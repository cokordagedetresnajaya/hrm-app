<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::where("email", "samplecompany1@example.com")->first();

        $payroll = Payroll::create([
            "company_id" => $company->id,
            "year" => Carbon::now()->year,
            "month" => Carbon::now()->month
        ]);
    }
}
