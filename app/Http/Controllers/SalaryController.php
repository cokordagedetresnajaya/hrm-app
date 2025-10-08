<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function getNetSalary(Request $request)
    {
        $data = $request->all();

        if (!$data['payroll_id'] || !$data['employee_id']) {
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'payroll_id or employee_id are required' 
            ], 400);
        }

        $salary = Salary::where('payroll_id', $data['payroll_id'])->where('employee_id', $data['employee_id'])->first();

        if ($salary) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Get salary successfully',
                'data' => $salary->breakdown->calculateNetPay()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Salary not found',
            ], 404);
        }
    }
}
