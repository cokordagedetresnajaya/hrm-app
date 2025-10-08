<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($company->departments as $department) {
                foreach ($department->designations as $designation) {
                    for ($i=0; $i < 3; $i++) { 
                        Employee::create([
                            "designation_id" => $designation->id,
                            "name" => $faker->name(),
                            "email" => $faker->unique()->safeEmail(),
                            "phone" => $faker->phoneNumber,
                            "address" => $faker->address,
                            "marital_status" => $faker->randomElement(['single', 'married', 'divorced', 'widowed']),
                            "dependents_count" => $faker->numberBetween(0, 3),
                        ]);
                    }
                }
            }
        }
    }
}
