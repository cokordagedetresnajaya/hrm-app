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
                                        <th style="width: 10px">#</th>
                                        <th>Department Name</th>
                                        <th>Number of Designations</th>
                                        <th>Number of Employees</th>
                                        <th style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $key => $department)
                                        <tr class="align-middle">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="Department Name">{{ $department->name }}</td>
                                            <td data-label="Number of Designations">{{ $department->designations->count() }}
                                            </td>
                                            <td data-label="Number of Employees">
                                                {{ $department->designations->flatMap->employees->count() }}</td>
                                            <td data-label="Actions">
                                                <a href="{{ route('departments.edit', $department->id) }}"
                                                    class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                                <form id="delete-form-{{ $department->id }}" class="d-inline-block"
                                                    action="{{ route('departments.delete', $department->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation(event, {{ $department->id }})"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $departments->links() }}
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
