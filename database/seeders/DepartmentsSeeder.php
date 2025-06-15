<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        foreach ($companies as $company) {
            $departments = $company->departments()->createMany([
                [
                    "name" => "Engineering",
                ],
                [
                    "name" => "Human Resorces"
                ],
                [
                    "name" => "Finance"
                ],
                [
                    "name" => "Marketing"
                ]
            ]);
        }

        foreach ($departments as $department) {
            switch ($department->name) {
                case 'Engineering':
                    $designations = [
                        'Software Engineer',
                        'Senior Software Engineer',
                        'Engineering Manager',
                        'Director of Engineering'
                    ];
                    break;
                case 'Human Resources':
                    $designations = [
                        'HR Coordinator',
                        'HR Manager',
                        'HR Director',
                        'VP of HR'
                    ];
                    break;
                case 'Finance':
                    $designations = [
                        'Accountant',
                        'Senior Accountant',
                        'Finance Manager',
                        'Chief Financial Officer'
                    ];
                    break;
                case 'Marketing':
                    $designations = [
                        'Marketing Specialist',
                        'Marketing Manager',
                        'Director of Marketing',
                        'VP of Marketing'
                    ];
                    break;
                default:
                    $designations = [];
                    break;
            }

            foreach ($designations as $designation) {
                $department->designations()->create([
                    'name' => $designation
                ]);
            }
        }
    }
}
