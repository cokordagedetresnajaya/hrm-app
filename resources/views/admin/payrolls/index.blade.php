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
            <!--begin::Row-->
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('payrolls.generate') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-10">
                                        <input name="monthYear" type="text" id="monthYear"
                                            class="form-control datepicker @error('monthYear') is-invalid @enderror"
                                            autocomplete="off" placeholder="Select month year"
                                            value="{{ old('monthYear') }}">
                                        @error('monthYear')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <button type="submit" class="btn btn-primary mt-2 mt-sm-0 w-100 w-sm-auto">Generate
                                            Payroll</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                @foreach ($payrolls as $payroll)
                                    <div class="col-md-4 mt-4">
                                        <a class="text-decoration-none" href="{{ route('payrolls.show', $payroll->id) }}">
                                            <div class="bg-dark py-3 px-4 text-white rounded">
                                                <h2 class="mb-2">{{ $payroll->month_string }}</h2>
                                                <p>{{ getCompany()->name }}</p>
                                                <div class="d-flex flex-column align-items-end">
                                                    <p class="m-0">IDR</p>
                                                    <p class="m-0 fs-4 fw-bold">
                                                        {{ number_format($payroll->salaries?->sum('gross_salary')) }}</p>

                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
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
