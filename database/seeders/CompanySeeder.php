<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company1 = Company::create([
            "name" => "Sample Company 1",
            "email" => "samplecompany1@example.com",
            "logo" => "default-logo.png",
            "website" => "https://samplecompany1.com"
        ]);

        $company2 = Company::create([
            "name" => "Sample Company 2",
            "email" => "samplecompany2@example.com",
            "logo" => "",
            "website" => "https://samplecompany2.com"
        ]);
    }
}
