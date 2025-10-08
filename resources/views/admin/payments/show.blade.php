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
            <div class="row mb-2">
                <div class="col-12 text-right">
                    <a href="{{ route('payments.index'); }}" class="btn btn-primary d-inline-block">Back To Payment List</a>
                </div>
            </div>
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="payroll" class="form-label">Payroll Period</label>
                                    <input name="payroll" type="text"
                                        class="form-control id="payroll"
                                        value="{{ \Carbon\Carbon::createFromDate($payment->payroll->year, $payment->payroll->month, 1)->format('F Y'); }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="employee" class="form-label">Employee Name</label>
                                    <input name="employee" type="text"
                                        class="form-control" id="employee"
                                        value="{{ $payment->employee->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input name="amount" type="text"
                                        class="form-control" id="amount"
                                        value="{{ 'IDR ' . number_format($payment->amount, 0, ',', '.') }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="paymentDate" class="form-label">Payment Date</label>
                                    <input name="paymentDate" type="text"
                                        class="form-control" id="paymentDate"
                                        value="{{ date('d F Y', strtotime($payment->payment_date)); }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="paymentMethod" class="form-label">Payment Method</label>
                                    <input name="paymentMethod" type="text"
                                        class="form-control" id="paymentMethod"
                                        value="{{ $payment->payment_method == 'cash' ? 'Cash' : ($payment->payment_method == 'bank_transfer' ? 'Bank Transfer' : ''); }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="reference" class="form-label">Reference</label>
                                    <input name="reference" type="text"
                                        class="form-control" id="reference"
                                        value="{{ $payment->reference }}" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection
