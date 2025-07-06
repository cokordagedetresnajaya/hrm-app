<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveEmployeeRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index(): Response
    {
        $title = 'Employees';
        $employees = Employee::inCompany()->paginate(10);
        return response()->view('admin.employees.index', compact('title', 'employees'));
    }

    public function create(): Response
    {
        $title = 'Create Employee';
        $departments = Department::inCompany()->get();
        return response()->view('admin.employees.create', compact('title', 'departments'));
    }

    public function store(SaveEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Employee::create($data);
        session()->flash('success', 'Employee created successfully.');
        return redirect()->route('employees.index');
    }

    public function edit(int $id): Response
    {
        $title = 'Edit Employee';
        $employee = Employee::findOrFail($id);
        $departments = Department::inCompany()->get();
        $designations = Designation::where('department_id', $employee->designation->department_id)->get();
        return response()->view('admin.employees.edit', compact('title', 'employee', 'departments', 'designations'));
    }

    public function update(int $id, SaveEmployeeRequest $request): RedirectResponse
    {
        $employee = Employee::findOrFail($id);
        $data = $request->validated();
        $employee->update($data);
        session()->flash('success', 'Employee updated successfully.');
        return redirect()->route('employees.index');
    }

    public function delete(int $id): RedirectResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        session()->flash('success', 'Employee deleted successfully.');
        return redirect()->route('employees.index');
    }

    public function getEmployeesAutocomplete(Request $request)
    {
        $keyword = $request->get('keyword');
        $employees = Employee::inCompany()->where('name', 'like', '%' . $keyword . '%')->limit(10)->get(['id', 'name']);

        $data = $employees->map(function ($item) {
            return [
                'label' => $item->name,
                'value' => $item->id . '-' . $item->name,
            ];
        });

        return response()->json($data);
    }
}
