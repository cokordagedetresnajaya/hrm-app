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
                    <h3 class="mb-0">{{ $payroll->month_string . ' ' . getCompany()->name . "'s " . $title }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $payroll->month_string . ' ' . getCompany()->name . "'s " . $title }}</li>
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
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table responsive-stack">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10px">#</th>
                                        <th class="text-center">Employee Details</th>
                                        <th class="text-center">Gross Salary</th>
                                        <th class="text-center">BPJS Kesehatan</th>
                                        <th class="text-center">BPJS JHT</th>
                                        <th class="text-center">BPJS JP</th>
                                        <th class="text-center">PPH 21</th>
                                        <th class="text-center">Net Pay</th>
                                        <th class="text-center" style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payroll->salaries as $key => $salary)
                                        <tr class="align-middle text-center">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="Employee Details">
                                                <h3 class="fs-5">{{ $salary->employee->name }}</h3>
                                                <h5 class="fs-6">{{ $salary->employee->designation->name }}</h5>
                                            </td>
                                            <td data-label="Gross Salary">
                                                IDR {{ number_format($salary->gross_salary) }}
                                            </td>
                                            <td data-label="BPJS Kesehatan">
                                                IDR {{ number_format($salary->breakdown->calculateBPJSKesehatan()) }}
                                            </td>
                                            <td data-label="BPJS JHT">
                                                IDR {{ number_format($salary->breakdown->calculateBPJSJHT()) }}
                                            </td>
                                            <td data-label="BPJS JP">
                                                IDR {{ number_format($salary->breakdown->calculateBPJSJP()) }}
                                            </td>
                                            <td data-label="PPH 21">
                                                IDR {{ number_format($salary->breakdown->calculatePPh21()) }}
                                            </td>
                                            <td data-label="Net Pay">
                                                IDR {{ number_format($salary->breakdown->calculateNetPay()) }}
                                            </td>
                                            <td data-label="Actions">
                                                <form id="generate-payslip-{{ $salary->id }}" class="d-inline-block"
                                                    action="{{ route('payrolls.generate_payslip', $salary->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bi bi-file-earmark-pdf-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset('js/helper.js') }}"></script>
    <script>
        $(function() {
            flatpickr(".datepicker", {
                dateFormat: "F Y",
                allowInput: false,
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false,
                        dateFormat: "F Y",
                        altFormat: "F Y",
                        theme: "light"
                    })
                ]
            });
        });
    </script>
@endpush
