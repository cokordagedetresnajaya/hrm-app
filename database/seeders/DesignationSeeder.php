<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::where("name", "Finance")->first();

        $designation = Designation::create([
            "name" => "Auditor",
            "department_id" => $department->id
        ]);

        $designation = Designation::create([
            "name" => "Accountant",
            "department_id" => $department->id
        ]);

        $department = Department::where("name", "Information Technology (IT)")->first();

        $designation = Designation::create([
            "name" => "Software Engineer",
            "department_id" => $department->id
        ]);

        $designation = Designation::create([
            "name" => "Project Manager",
            "department_id" => $department->id
        ]);
    }
}
