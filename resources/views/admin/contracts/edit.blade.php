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
                        <li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contracts</a></li>
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
                    <form method="POST" action="{{ route('contracts.update', $contract->id) }}">
                        @csrf
                        @method('PATCH')
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="employee" class="form-label">Employee</label>
                                        <input id="employee" name="employee"
                                            class="form-control @error('employee') is-invalid @enderror"
                                            value="{{ $contract->employee_id . '-' . $contract->employee->name }}">
                                        @error('employee')
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
                                                <option value="{{ $department->id }}" {{ $contract->designation->department_id == $department->id ? 'selected' : '' }}>
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
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}"
                                                    {{ $designation->id == $contract->designation_id ? 'selected' : '' }}>
                                                    {{ $designation->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('designation_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input name="start_date" type="text" id="start_date"
                                            class="form-control datepicker @error('start_date') is-invalid @enderror"
                                            autocomplete="off" placeholder="Select start date"
                                            value="{{ date('d/m/Y', strtotime($contract->start_date)) }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input name="end_date" type="text" id="end_date"
                                            class="form-control datepicker @error('end_date') is-invalid @enderror"
                                            autocomplete="off" placeholder="Select end date" value="{{ date('d/m/Y', strtotime($contract->end_date)) }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rate" class="form-label">Rate (IDR)</label>
                                        <input name="rate" type="number" id="rate"
                                            class="form-control @error('rate') is-invalid @enderror"
                                            value="{{ $contract->rate }}">
                                        @error('rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rate" class="form-label">Rate Type</label>
                                        <select name="rate_type" id="rate_type"
                                            class="form-control @error('rate_type') is-invalid @enderror">
                                            <option value="">Select Rate Type</option>
                                            <option value="daily" {{ $contract->rate_type == 'daily' ? 'selected' : '' }}>
                                                Daily</option>
                                            <option value="monthly" {{ $contract->rate_type == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                        </select>
                                        @error('rate_type')
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
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
    <script>
        $(function() {
            setupDependentSelect(
                '#department',
                '#designation',
                '/designations/by-department/:id',
                null,
                'Choose Designation'
            );

            flatpickr(".datepicker", {
                dateFormat: "d/m/Y",
                allowInput: true
            });

            $("#employee").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('employees.getEmployeesAutocomplete') }}",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1
            });
        });
    </script>
@endpush
