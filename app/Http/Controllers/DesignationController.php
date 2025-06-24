<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDepartmentRequest;
use App\Http\Requests\SaveDesignationRequest;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DesignationController extends Controller
{
    public function index(): Response
    {
        $title = 'Designations';
        $designations = Designation::inCompany()->orderBy('department_id')
            ->latest()
            ->paginate(perPage: 10);

        return response()->view('admin.designations.index', compact('title', 'designations'));
    }

    public function create(): Response
    {
        $title = 'Create Designation';
        $departments = Department::inCompany()->get();
        return response()->view('admin.designations.create', compact('title', 'departments'));
    }

    public function store(SaveDesignationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Designation::create($data);
        session()->flash('success', 'Designation created successfully.');
        return redirect()->route('designations.index');
    }

    public function edit(int $id): Response
    {
        $designation = Designation::findOrFail($id);
        $title = 'Edit Designation';
        $departments = Department::inCompany()->get();
        return response()->view('admin.designations.edit', compact('title','designation','departments'));
    }

    public function update(int $id, SaveDesignationRequest $request): RedirectResponse
    {
        $designation = Designation::findOrFail($id);
        $data = $request->validated();
        $designation->update($data);
        session()->flash('success', 'Designation updated successfully.');
        return redirect()->route('designations.index');
    }

    public function delete(int $id): RedirectResponse
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
        session()->flash('success', 'Designation deleted successfully.');
        return redirect()->route('designations.index');
    }
}
