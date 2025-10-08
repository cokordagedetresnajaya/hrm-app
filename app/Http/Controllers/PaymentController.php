<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePaymentRequest;
use App\Models\Payment;
use App\Models\Payroll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index()
    {
        $title = "Payments";
        $query = Payment::inCompany();
        $payments = $query->paginate(10);
        return response()->view('admin.payments.index', compact('title', 'payments'));
    }

    public function create()
    {
        $title = "Create Payment";
        $payrolls = Payroll::inCompany()->get();
        return response()->view('admin.payments.create', compact('title', 'payrolls'));
    }

    public function store(SavePaymentRequest $request)
    {
        $data = $request->validated();
        $employee_data = explode('-', $data['employee']);
        if (count($employee_data) != 2 || !is_numeric($employee_data[0])) {
            throw ValidationException::withMessages(['employee' => 'Employee value invalid.']);
        } else {
            $data['payment_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['payment_date'])->format('Y-m-d');
            $data['employee_id'] = $employee_data[0];
            unset($employee);
            $payment = Payment::where('employee_id', $data['employee_id'])->where('payroll_id', $data['payroll_id'])->first();
            if ($payment) {
                throw ValidationException::withMessages(['employee_id' => 'This employee and payroll period already have payment.']);
            }

            Payment::create($data);
            session()->flash('success', 'Payment created successfully.');
            return redirect()->route('payments.index');
        }
    }

    public function show($id)
    {
        $title = 'Payment Detail';
        $payment = Payment::findOrFail($id);
        return response()->view('admin.payments.show', compact('title','payment'));
    }

    public function delete(int $id): RedirectResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        session()->flash('success', 'Payment deleted successfully.');
        return redirect()->route('payments.index');
    }
}
