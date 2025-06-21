<!--begin::Sidebar Menu-->
<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
    <li class="nav-header">ACTIVE COMPANY</li>
    <li class="nav-header">
        <form id="switchCompanyForm" method="POST" action="{{ route('companies.switch') }}">
            @csrf
            <select name="company_id" id="" class="form-control" onchange="document.getElementById('switchCompanyForm').submit();">
                <option value="">Select Company</option>
                @foreach(auth()->user()->companies as $company)
                    <option value="{{ $company->id }}" {{ session('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
        </form>
    </li>
    <li class="nav-item">
        <hr class="dropdown-divider my-1">
    </li>
    <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link {{ isActiveRoute('dashboard') }}">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item {{ isMenuOpen(['companies.*']) }}">
        <a href="#" class="nav-link {{ isActiveRoute('companies.*') }}">
            <i class="nav-icon bi bi-building"></i>
            <p>
                Companies
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('companies.create') }}" class="nav-link {{ isActiveRoute('companies.create') }}">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Create Company</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('companies.index') }}" class="nav-link {{ isActiveRoute('companies.index') }}">
                    <i class="nav-icon bi bi-list-ul"></i>
                    <p>Companies List</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item {{ isMenuOpen(['departments.*']) }}">
        <a href="#" class="nav-link {{ isActiveRoute('departments.*') }}">
            <i class="nav-icon bi bi-diagram-3"></i>
            <p>
                Departments
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('departments.create') }}"
                    class="nav-link {{ isActiveRoute('departments.create') }}">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Create Department</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('departments.index') }}" class="nav-link {{ isActiveRoute('departments.index') }}">
                    <i class="nav-icon bi bi-list-ul"></i>
                    <p>Departments List</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item {{ isMenuOpen(['designations.*']) }}">
        <a href="#" class="nav-link {{ isActiveRoute('designations.*') }}">
            <i class="nav-icon bi bi-briefcase"></i>
            <p>
                Designations
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('designations.create') }}"
                    class="nav-link {{ isActiveRoute('designations.create') }}">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Create Designation</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('designations.index') }}"
                    class="nav-link {{ isActiveRoute('designations.index') }}">
                    <i class="nav-icon bi bi-list-ul"></i>
                    <p>Designations List</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item {{ isMenuOpen(['employees.*']) }}">
        <a href="#" class="nav-link {{ isActiveRoute('employees.*') }}">
            <i class="nav-icon bi bi-people"></i>
            <p>
                Employees
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('employees.create') }}" class="nav-link {{ isActiveRoute('employees.create') }}">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Create Employee</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('employees.index') }}" class="nav-link {{ isActiveRoute('employees.index') }}">
                    <i class="nav-icon bi bi-list-ul"></i>
                    <p>Employees List</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item {{ isMenuOpen(['contracts.*']) }}">
        <a href="#" class="nav-link {{ isActiveRoute('contracts.*') }}">
            <i class="nav-icon bi bi-file-text"></i>
            <p>
                Contracts
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('contracts.create') }}" class="nav-link {{ isActiveRoute('contracts.create') }}">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Create Contract</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('contracts.index') }}" class="nav-link {{ isActiveRoute('contracts.index') }}">
                    <i class="nav-icon bi bi-list-ul"></i>
                    <p>Contracts List</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon bi bi-calculator"></i>
            <p>
                Accounting
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('payrolls.index') }}" class="nav-link">
                    <i class="nav-icon bi bi-cash"></i>
                    <p>Payrolls</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../widgets/info-box.html" class="nav-link">
                    <i class="nav-icon bi bi-receipt"></i>
                    <p>Payroll Payments</p>
                </a>
            </li>
        </ul>
    </li>
</ul>
<!--end::Sidebar Menu-->
