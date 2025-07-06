<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    public function index(): Response
    {
        $title = 'Departments';
        $departments = Department::query()->inCompany()->latest()->paginate(10);
        return response()->view('admin.departments.index', compact('title', 'departments'));
    }

    public function create(): Response
    {
        $title = 'Create Department';
        return response()->view('admin.departments.create', compact('title'));
    }

    public function store(SaveDepartmentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $department = new Department();
        $department->name = $data['name'];
        $department->company_id = session('company_id');
        $department->save();

        session()->flash('success', 'Department created successfully.');
        return redirect()->route('departments.index');
    }

    public function edit(int $id): Response
    {
        $title = 'Edit Department';
        $department = Department::findOrFail($id);
        return response()->view('admin.departments.edit', compact('title','department'));
    }

    public function update(int $id, SaveDepartmentRequest $request ): RedirectResponse
    {
        $department = Department::findOrFail($id);

        $data = $request->validated();

        $department->update($data);
        
        session()->flash('success', 'Department updated successfully.');
        return redirect()->route('departments.index');
    }

    public function delete(int $id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        session()->flash('success', 'Department deleted successfully.');
        return redirect()->route('departments.index');
    }
}
