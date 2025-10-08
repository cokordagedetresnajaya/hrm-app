<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePayrollRequest;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Salary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PayrollController extends Controller
{
    public function index(): Response
    {
        $title = 'Payrolls List';
        $payrolls = Payroll::inCompany()->orderBy('year','desc')->orderBy('month','desc')->paginate(10);
        return response()->view('admin.payrolls.index', compact('title','payrolls'));
    }

    public function generatePayroll(GeneratePayrollRequest $request)
    {
        $data = $request->validated();
        $date = Carbon::parse($data['monthYear']);

        $isPayrollExists = Payroll::inCompany()->where('month', $date->format('m'))->where('year', $date->format('Y'))->exists();
        
        if ($isPayrollExists) {
            throw ValidationException::withMessages(['monthYear' => 'Payroll already generated for this month.']);
        } else {
            $payroll = new Payroll();
            $payroll->month = $date->format('m');
            $payroll->year = $date->format('Y');
            $payroll->company_id = session('company_id');
            $payroll->save();

            $employees = Employee::inCompany()->get();

            foreach ($employees as $employee) {
                $contract = $employee->getActiveContract($date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString());
                if ($contract) {
                    $payroll->salaries()->create([
                        'employee_id' => $employee->id,
                        'gross_salary' => $contract->getTotalEarnings($date->format('Y-m'))
                    ]);
                }
            }
            session()->flash('success', 'Payroll generated successfully.');
            return redirect()->route('payrolls.index');
        }
    }

    public function show(int $id): Response
    {
        $title = "Payroll Breakdown";
        $payroll = Payroll::inCompany()->findOrFail($id);
        return response()->view('admin.payrolls.show', compact('title', 'payroll'));
    }

    public function updatePayroll(int $id)
    {
        $payroll = Payroll::inCompany()->find($id);
        $payroll->salaries()->delete();
        $employees = Employee::inCompany()->get();
        foreach ($employees as $employee) {
            $contract = $employee->getActiveContract($payroll->year . '-' . $payroll->month . '-01', $payroll->year . '-' . $payroll->month . '-31');
            if ($contract) {
                $payroll->salaries()->create([
                    'employee_id' => $employee->id,
                    'gross_salary' => $contract->getTotalEarnings($payroll->year . '-' . $payroll->month)
                ]);
            }
        }
        session()->flash('success', 'Payroll updated successfully.');
    }

    public function generatePayslip(int $id)
    {
        $salary = Salary::find($id);
        $pdf = Pdf::loadView('pdf.payslip', compact('salary'));
        $pdf->setPaper('A4', 'portrait');
        $filepath = storage_path(Str::slug($salary->employee->name) . '-payslip.pdf');
        $pdf->save($filepath);
        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}
