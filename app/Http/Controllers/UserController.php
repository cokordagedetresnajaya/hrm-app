<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $title = "Users";
        $users = User::paginate(10);
        return response()->view('admin.users.index', compact('title','users'));
    }

    public function create()
    {
        $title = 'Create New User';
        $companies = Company::all();
        $roles = Role::all();
        return response()->view('admin.users.create', compact('title','companies','roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required'],
            'companies' => ['required','array'],
            'companies.*' => ['integer', 'exists:companies,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->companies()->attach($validated['companies']);
        $user->assignRole($validated['role']);

        return redirect(route('users.index'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $companies = Company::all();
        $roles = Role::all();
        $title = "Edit User";
        $selectedCompanies = $user->companies->pluck('id')->toArray();
        return response()->view('admin.users.edit', compact('title','user','companies','roles','selectedCompanies'));
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required'],
            'companies' => ['required','array'],
            'companies.*' => ['integer', 'exists:companies,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email']
        ];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Update companies (pivot)
        $user->companies()->sync($validated['companies']);

        // Update role (Spatie)
        $user->syncRoles([$validated['role']]);
        session()->flash('success', 'User updated successfully.');

        $companies = $user->companies->pluck('id')->toArray();
        if ($user->id == Auth::user()->id && !in_array(session('company_id'), $companies)) {
            session()->forget('company_id');
            return redirect()->route('dashboard');
        }
        return redirect()->route('users.index');
    }

    public function dashboard(): Response
    {
        $title = 'Dashboard';
        $is_company_selected = session("company_id") ? true : false;

        $summary = [
            "total_employees" => 0,
            "total_departments" => 0,
            "total_active_contracts" => 0,
            "total_pending_payrolls" => 0
        ];

        $employee_by_department = [
            'count' => [],
            'departments' => []
        ];

        $employee_growth = [
            'new_hires' => [],
            'active_employees' => []
        ];

        $payroll_summary = [];

        if ($is_company_selected) {
            // Summary data di card
            $today = now()->format('Y-m-d');

            $total_employees = Employee::inCompany()->count();
            $total_departments = Department::inCompany()->count();
            $total_active_contracts = Contract::inCompany()->where('end_date', '>=', $today)->count();
            $total_pending_payroll = Payroll::pending()
            ->where('company_id', session('company_id'))
            ->count();

            $summary = [
                "total_employees" => $total_employees,
                "total_departments" => $total_departments,
                "total_active_contracts" => $total_active_contracts,
                "total_pending_payrolls" => $total_pending_payroll
            ];

            // Summary data pembagian employee per department
            $employee_by_department_counts = Department::inCompany()->withCount('employees')->get();

            foreach ($employee_by_department_counts as $dept) {
                $employee_by_department['departments'][] = $dept->name;
                $employee_by_department['count'][] = $dept->employees_count;
            }


            // Summary data employee growth (new hires)
            $year = now()->year;

            $new_hires = Employee::withTrashed()
            ->inCompany()->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

            $new_hires = collect(range(1, 12))->mapWithKeys(function ($month) use ($new_hires) {
                return [$month => $new_hires[$month] ?? 0];
            })->toArray();

            $employee_growth['new_hires'] = $new_hires;

            // Summary data employee growth (total active)
            $company_id = session('company_id');
            $active_employees = collect(range(1,12))->mapWithKeys(function($month) use ($company_id, $year) {
                $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

                $count = Employee::inCompany()
                    ->where('created_at', '<=', $endOfMonth)
                    ->whereNull('deleted_at')
                    ->count();

                return [$month => $count];
            })->toArray();

            $employee_growth['active_employees'] = $active_employees;

            // Payroll Summary Per Month
            $payroll_summary = collect(range(1,12))->mapWithKeys(function($month) use ($year){
                // Hitung total amount dari tabel salaries yang terhubung ke payroll di bulan tersebut
                $total = Payroll::inCompany()
                    ->where('year', $year)
                    ->where('month', $month)
                    ->join('salaries', 'salaries.payroll_id', '=', 'payrolls.id')
                    ->sum('salaries.gross_salary');

                return [$month => $total];
            })->toArray();
        }

        return response()->view('admin.dashboard', compact('title', 'is_company_selected', 'summary', 'employee_by_department', 'employee_growth', 'payroll_summary'));
    }

    public function profile()
    {
        $title = "Profile";
        $user = Auth::user();
        return response()->view('admin.users.profile', compact('title', 'user'));
    }

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $data = $request->validated();
        
        $data = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        
        $user->update($data);
        session()->flash('success', 'Profile updated successfully.');
        return redirect()->route('profile');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == Auth::user()->id) {
            session()->flash('error', 'You cannot delete yourself');
            return redirect()->route('users.index');
        }
        $user->delete();
        session()->flash('success', 'User deleted successfully.');
        return redirect()->route('users.index');
    }
}
