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
                        <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
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
                    <form method="POST" action="{{ route('payments.store') }}">
                        @csrf
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="employee" class="form-label">Employee</label>
                                        <input id="employee" name="employee"
                                            class="form-control @error('employee') is-invalid @enderror"
                                            value="{{ old('employee') }}">
                                        @error('employee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payroll" class="form-label">Payroll Period</label>
                                        <select name="payroll_id"
                                            class="form-select @error('payroll_id') is-invalid @enderror" id="payroll">
                                            <option value="">Choose Payroll Period</option>
                                            @foreach ($payrolls as $payroll)
                                                <option value="{{ $payroll->id }}" {{ old('payroll_id') == $payroll->id ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('F Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('payroll_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input name="amount" type="number" id="amount"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            placeholder="Enter amount" value="{{ old('amount') ? old('amount') : 0 }}" readonly>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment_date" class="form-label">Payment Date</label>
                                        <input name="payment_date" type="text" id="payment_date"
                                            class="form-control datepicker @error('payment_date') is-invalid @enderror"
                                            autocomplete="off" placeholder="Select payment date"
                                            value="{{ old('payment_date') }}">
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select name="payment_method"
                                            class="form-select @error('payment_method') is-invalid @enderror"
                                            id="designation">
                                            <option value="">Choose Payment Method</option>
                                            <option value="cash">Cash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reference" class="form-label">Reference</label>
                                        <input name="reference" type="text" id="reference"
                                            class="form-control @error('reference') is-invalid @enderror"
                                            placeholder="Enter reference"
                                            value="{{ old('reference') }}">
                                        @error('reference')
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
    <script>
        $(function() {
            flatpickr(".datepicker", {
                dateFormat: "d/m/Y",
                allowInput: false
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

            $('#employee').on('change', function(){
                if ($('#payroll').val() !== '' && $(this).val() !== '' && isEmployeeValid($(this).val())) {
                    const [employeeId, employeeName] = $(this).val().split(/-(.+)/);
                    const payrollId = $('#payroll').val();

                    getEmployeeSalary(payrollId, employeeId);
                }
            });

            $('#payroll').on('change', function(){
                if ($('#payroll').val() !== '' && $('#employee').val() !== '' && isEmployeeValid($('#employee').val())) {
                    const [employeeId, employeeName] = $('#employee').val().split(/-(.+)/);
                    const payrollId = $(this).val();

                    getEmployeeSalary(payrollId, employeeId);
                }
            });
        });

        function isEmployeeValid(input){
            const regex = /^\d+-[A-Za-z0-9]+( [A-Za-z0-9]+)*$/;
            return regex.test(input);
        }

        function getEmployeeSalary(payrollId, employeeId){
            $.ajax({
                url: "{{ route('salaries.get-net') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    payroll_id: payrollId,
                    employee_id: employeeId
                },
                success: function(response) {
                    console.log(response);
                    $('#amount').val(response.data);
                }
            })
        }
        
    </script>
@endpush
