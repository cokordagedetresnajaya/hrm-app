<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::where("email", "employee1@example.com")->first();

        $contract = new Contract();
        $contract->employee_id = $employee->id;
        $contract->designation_id = $employee->designation_id;
        $contract->start_date = Carbon::now();
        $contract->end_date = Carbon::now()->addDays(3);
        $contract->rate_type = "hourly";
        $contract->rate = 20;
        $contract->save();
    }
}
