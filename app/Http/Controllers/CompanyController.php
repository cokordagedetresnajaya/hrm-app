<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCompanyRequest;
use App\Http\Requests\SwitchCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): Response
    {
        $title = 'Companies';
        $companies = Company::forUser()->latest()->paginate(10);
        return response()->view('admin.companies.index', compact('title', 'companies'));
    }

    public function create(): View
    {
        $title = 'Create Company';
        return view('admin.companies.create', compact('title'));
    }

    public function store(SaveCompanyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (!empty($data['logo'])) {
            $data['logo'] = $data['logo']->store('companies', 'public');
        }
        $company = Company::create($data);
        $company->users()->attach(Auth::id());
        session()->flash('success', 'Company created successfully.');
        return redirect()->route('companies.index');
    }

    public function edit(int $id): View
    {
        $company = Company::findOrFail($id);
        if (!auth()->user()->hasCompany($company->id)) {
            abort(403, "You cannot edit this company");
        }
        $title = 'Edit Company';
        return view('admin.companies.edit', compact('title', 'company'));
    }

    public function update(int $id, SaveCompanyRequest $request)
    {
        $company = Company::findOrFail($id);

        $data = $request->validated();

        if (!empty($data['logo'])) {
            if($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $data['logo']->store('companies', 'public');
        }

        $company->update($data);
        session()->flash('success', 'Company updated successfully.');
        return redirect()->route('companies.index');
    }

    public function delete($id): RedirectResponse
    {
        $company = Company::findOrFail($id);
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        $company->delete();
        session()->flash('success', 'Company deleted successfully.');
        return redirect()->route('companies.index');
    }

    public function switch(SwitchCompanyRequest $request)
    {
        $data = $request->validated();
        session(['company_id' => $data['company_id']]);
        return redirect()->back()->with('success', 'Company switched successfully.');
    }
}
