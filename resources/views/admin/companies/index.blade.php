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
                                        <th>Company Name</th>
                                        <th>Number of Employees</th>
                                        <th style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $key => $company)
                                        <tr class="align-middle">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td class="image-text-cell" data-label="Company Name">
                                                <div class="image-text-wrapper d-flex align-items-center">
                                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }} Logo"
                                                        class="sm-round-logo me-2">
                                                    <span>{{ $company->name }}</span>
                                                </div>
                                            </td>
                                            <td data-label="Number of Employees">
                                                {{ $company->departments->flatMap->designations->flatMap->employees->count() }}
                                            </td>
                                            <td data-label="Actions">
                                                <a href="{{ route('companies.edit', $company->id) }}"
                                                    class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                                <form id="delete-form-{{ $company->id }}" class="d-inline-block"
                                                    action="{{ route('companies.delete', $company->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"><i
                                                            class="bi bi-trash-fill"
                                                            onclick="deleteConfirmation(event, {{ $company->id }})"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $companies->links() }}
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
