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
                                    <tr>
                                        <th class="text-center" style="width: 10px">#</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Designation</th>
                                        <th style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $key => $employee)
                                        <tr class="align-middle text-center">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="Employee Name">
                                                <p class="m-0">{{ $employee->name }}</p>
                                                <small>{{ $employee->email }}</small>
                                            </td>
                                            <td data-label="Designation">
                                                <p class="m-0">{{ $employee->designation->name }}</p>
                                                <small>{{ $employee->designation->department->name }}</small>
                                            </td>
                                            <td data-label="Actions">
                                                <a href="{{ route('employees.edit', $employee->id) }}"
                                                    class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                                <form id="delete-form-{{ $employee->id }}" class="d-inline-block"
                                                    action="{{ route('employees.delete', $employee->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation(event, {{ $employee->id }})"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $employees->links() }}
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
