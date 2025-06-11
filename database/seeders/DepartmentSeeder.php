<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::where("email", "samplecompany1@example.com")->first();

        $itDepartment = Department::create([
            "name" => "Information Technology (IT)",
            "company_id" => $company->id
        ]);

        $financeDepartment = Department::create([
            "name" => "Finance",
            "company_id" => $company->id
        ]);
    }
}
