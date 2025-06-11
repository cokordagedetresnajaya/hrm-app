<?php

namespace Database\Seeders;

use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designation = Designation::where("name", "Auditor")->first();

        $employee = Employee::create([
            "designation_id" => $designation->id,
            "name" => "Employee 1",
            "email" => "employee1@example.com",
            "phone" => "123456789",
            "address" => "Sample Address"
        ]);

        $employee = Employee::create([
            "designation_id" => $designation->id,
            "name" => "Employee 2",
            "email" => "employee2@example.com",
            "phone" => "123456789",
            "address" => "Sample Address"
        ]);
    }
}
