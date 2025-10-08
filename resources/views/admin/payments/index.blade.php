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
                    <h3 class="mb-0">{{ getCompany()->name . ' ' . $title }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ getCompany()->name . ' ' . $title }}</li>
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
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">Create New Payment</a>
                </div>
            </div>
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table responsive-stack">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10px">#</th>
                                        <th class="text-center">Employee Details</th>
                                        <th class="text-center">Payroll Period</th>
                                        <th class="text-center" style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $key => $payment)
                                        <tr class="align-middle text-center">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="Employee Details">
                                                <h3 class="fs-5">{{ $payment->employee->name }}</h3>
                                                <h5 class="fs-6">{{ $payment->employee->designation->name }}</h5>
                                            </td>
                                            <td data-label="Payroll Period">
                                                {{ \Carbon\Carbon::createFromDate($payment->payroll->year, $payment->payroll->month, 1)->format('F Y'); }}
                                            </td>
                                            <td data-label="Actions">
                                                <a href="{{ route('payments.show', $payment->id); }}" class="text-white btn btn-warning">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                <form id="delete-form-{{ $payment->id }}" class="d-inline-block"
                                                    action="{{ route('payments.delete', $payment->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation(event, {{ $payment->id }})"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $payments->links() }}
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
@endpush
