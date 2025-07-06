<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveContractRequest;
use App\Models\Contract;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ContractController extends Controller
{
    public function index(Request $request): Response
    {
        $title = 'Contracts';
        $query = Contract::inCompany();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->searchByEmployee($search);
        }

        $contracts = $query->paginate(10);
        return response()->view('admin.contracts.index', compact('title', 'contracts'));
    }

    public function create(): Response
    {
        $title = 'Create Contract';
        $departments = Department::inCompany()->get();
        return response()->view('admin.contracts.create', compact('title', 'departments'));
    }

    public function store(SaveContractRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $employee_data = explode('-', $data['employee']);
        if (count($employee_data) != 2 || !is_numeric($employee_data[0])) {
            throw ValidationException::withMessages(['employee' => 'Employee value invalid.']);
        } else {
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['start_date'])->format('Y-m-d');
            $data['end_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['end_date'])->format('Y-m-d');
            $data['employee_id'] = $employee_data[0];
            $employee = Employee::findOrFail($data['employee_id']);
            if($employee->getActiveContract($data['start_date'], $data['end_date'])) {
                throw ValidationException::withMessages(['start_date' => 'Employee already has an active contract in this period.']);
            }
            Contract::create($data);
            session()->flash('success', 'Contract created successfully.');
            return redirect()->route('contracts.index');
        }
    }

    public function edit(int $id): Response
    {
        $contract = Contract::findOrFail($id);
        $title = 'Edit Contract';
        $departments = Department::inCompany()->get();
        $designations = Designation::inCompany()->where('department_id', $contract->designation->department_id)->get();
        return response()->view('admin.contracts.edit', compact('contract','title','departments','designations'));
    }

    public function update(int $id, SaveContractRequest $request): RedirectResponse
    {
        $contract = Contract::findOrFail($id);
        $data = $request->validated();
        $employee_data = explode('-', $data['employee']);
        if (count($employee_data) != 2 || !is_numeric($employee_data[0])) {
            throw ValidationException::withMessages(['employee' => 'Employee value invalid.']);
        } else {
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['start_date'])->format('Y-m-d');
            $data['end_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['end_date'])->format('Y-m-d');
            $data['employee_id'] = $employee_data[0];
            $employee = Employee::findOrFail($data['employee_id']);
            $activeContract = $employee->getActiveContract($data['start_date'], $data['end_date']);
            if($activeContract && $activeContract->id != $contract->id) {
                throw ValidationException::withMessages(['start_date' => 'Employee already has an active contract in this period.']);
            }
            $contract->update($data);
            session()->flash('success', 'Contract updated successfully.');
            return redirect()->route('contracts.index');
        }
    }

    public function delete(int $id): RedirectResponse
    {
        Contract::find($id)->delete();
        session()->flash('success', 'Contract deleted successfully.');
        return redirect()->route('contracts.index');
    }
}
