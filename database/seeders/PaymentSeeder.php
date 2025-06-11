<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payment;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::where("email", "employee1@example.com")->first();
        $payroll = Payroll::first();

        $payment = Payment::create([
            "employee_id" => $employee->id,
            "payroll_id" => $payroll->id,
            "amount" => 2000,
            "payment_date" => Carbon::now(),
            "payment_method" => "cash",
            "reference" => null
        ]);
    }
}
