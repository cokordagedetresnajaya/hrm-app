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
                            <table class="table responsive-stack">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 10px">#</th>
                                        <th>Employee Details</th>
                                        <th>Contract Details</th>
                                        <th>Rate</th>
                                        <th style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contracts as $key => $contract)
                                        <tr class="align-middle text-center">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="Employee Details">
                                                <p class="m-0 fw-semibold fs-5">
                                                    {{ $contract->employee->name }}
                                                </p>
                                                <p class="m-0">
                                                    {{ $contract->employee->email }}
                                                </p>
                                                <p class="m-0">
                                                    {{ $contract->employee->phone }}
                                                </p>
                                                <p class="m-0 fw-bold">
                                                    {{ $contract->employee->designation->name }}
                                                </p>
                                            </td>
                                            <td data-label="Contract Details">
                                                <p class="m-0">
                                                    Start: {{ $contract->start_date }}
                                                </p>
                                                <p class="m-0">
                                                    End: {{ $contract->end_date }}
                                                </p>
                                                <p class="m-0 fw-bold fs-6">
                                                    Duration: {{ $contract->duration }}
                                                </p>
                                            </td>
                                            <td data-label="Rate">
                                                USD {{ number_format($contract->rate) }} {{ $contract->rate_type }}
                                            </td>
                                            <td data-label="Actions">
                                                <a href="{{ route('contracts.edit', $contract->id) }}"
                                                    class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                                <form id="delete-form-{{ $contract->id }}" class="d-inline-block"
                                                    action="{{ route('contracts.delete', $contract->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation(event, {{ $contract->id }})"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $contracts->links() }}
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
