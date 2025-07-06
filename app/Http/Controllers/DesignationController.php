<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDesignationRequest;
use App\Http\Requests\EditDesignationRequest;
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
            ->paginate(10);

        return response()->view('admin.designations.index', compact('title', 'designations'));
    }

    public function create(): Response
    {
        $title = 'Create Designation';
        $departments = Department::inCompany()->get();
        return response()->view('admin.designations.create', compact('title', 'departments'));
    }

    public function store(CreateDesignationRequest $request): RedirectResponse
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

    public function update(int $id, EditDesignationRequest $request): RedirectResponse
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

    public function getByDepartment(int $id)
    {
        $designations = Designation::inCompany()->where('department_id', $id)
            ->get(['id', 'name']);
        return response()->json($designations);
    }
}
