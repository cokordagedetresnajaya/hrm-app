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
                                        <th class="text-center" style="width: 10px">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Companies</th>
                                        <th style="width: 140px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr class="align-middle text-center">
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="Name">
                                                {{ $user->name }}
                                            </td>
                                            <td data-label="Email">
                                                {{ $user->email }}
                                            </td>
                                            <td data-label="Role">
                                                @php
                                                $badgeColor = 'bg-danger';
                                                if ($user->getRoleNames()[0] == 'HRD') {
                                                    $badgeColor = "bg-warning";
                                                } else if($user->getRoleNames()[0] == 'Accountant') {
                                                    $badgeColor = "bg-primary";
                                                }
                                                @endphp
                                                <span class="badge {{ $badgeColor }}">
                                                    {{ $user->getRoleNames()[0] }}
                                                </span>
                                            </td>
                                            <td data-label="Companies">
                                                @foreach($user->companies as $company)
                                                    <span class="badge bg-primary">{{ $company->name }}</span>
                                                @endforeach
                                            </td>
                                            <td data-label="Actions">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                                <form id="delete-form-{{ $user->id }}" class="d-inline-block"
                                                    action="{{ route('users.delete', $user->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="deleteConfirmation(event, {{ $user->id }})"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $users->links() }}
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
