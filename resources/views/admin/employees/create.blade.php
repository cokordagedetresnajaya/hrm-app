@extends('layouts.app')
@section('title', $title)
@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $title }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Employee Name</label>
                                        <input name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Employee Email</label>
                                        <input name="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="maritalStatus" class="form-label">Marital Status</label>
                                        <select name="marital_status"
                                            class="form-select @error('marital_status') is-invalid @enderror"
                                            id="maritalStatus">
                                            <option value="">Select marital status</option>
                                            <option value="single"
                                                {{ old('marital_status') === 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married"
                                                {{ old('marital_status') === 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced"
                                                {{ old('marital_status') === 'divorced' ? 'selected' : '' }}>Divorced
                                            </option>
                                            <option value="widowed"
                                                {{ old('marital_status') === 'widowed' ? 'selected' : '' }}>Widowed
                                            </option>
                                        </select>
                                        @error('marital_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dependentsCount" class="form-label">Total Dependents</label>
                                        <select name="dependents_count"
                                            class="form-select @error('dependents_count') is-invalid @enderror"
                                            id="dependentsCount">
                                            <option value="" {{ old('dependents_count') === null ? 'selected' : '' }}>Select total dependents</option>
                                            @for ($i = 0; $i <= 2; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('dependents_count') != null && old('dependents_count') == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 0 ? '(No Dependents)' : '' }}
                                                </option>
                                            @endfor
                                            <option value="3" {{ old('dependents_count') == 3 ? 'selected' : '' }}>
                                                3 or More
                                            </option>
                                        </select>
                                        @error('dependents_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="department" class="form-label">Department</label>
                                        <select name="department_id"
                                            class="form-select @error('department_id') is-invalid @enderror"
                                            id="department">
                                            <option value="">Choose Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="designation" class="form-label">Designation</label>
                                        <select name="designation_id"
                                            class="form-select @error('designation_id') is-invalid @enderror"
                                            id="designation">
                                            <option value="">Choose Designation</option>
                                        </select>
                                        @error('designation_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input name="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input name="address" type="text"
                                            class="form-control @error('address') is-invalid @enderror" id="address"
                                            value="{{ old('address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <!-- /.card -->
                    </form>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

@push('scripts')
    <script src="{{ asset('js/helper.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupDependentSelect(
                '#department',
                '#designation',
                '/designations/by-department/:id',
                null,
                'Choose Designation'
            );
        });
    </script>
@endpush
