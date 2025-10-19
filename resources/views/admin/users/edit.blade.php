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
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
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
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PATCH')
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">User Name</label>
                                        <input name="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" id="name"
                                            value="{{ $user->name }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input name="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            value="{{ $user->email }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="companies" class="form-label">Companies</label>
                                        <select class="form-control @error('companies') is-invalid @enderror" name="companies[]" id="companies" multiple placeholder="Select companies" autocomplete="off">
                                            <option value="">Select Companies</option>
                                            @foreach($companies as $company)
                                            <option value="{{ $company->id }}" @selected(in_array($company->id, old('companies', $selectedCompanies ?? [])))>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('companies')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="role" class="form-label">Role</label>
                                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                            <option value="{{ $role->name }}"  {{ $user->getRoleNames()[0] == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input name="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input name="confirm_password" type="password"
                                            class="form-control @error('confirm_password') is-invalid @enderror"
                                            id="confirm_password">
                                        @error('confirm_password')
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
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
<script>
    $(document).ready(function(){
        const tomSelect = new TomSelect("#companies", {
            plugins: ['remove_button'],
        });

        $('.ts-wrapper').css('padding', 0);
        $('.ts-control').css('border', 'none');
        $('.ts-control input').css('font-size', '1rem');
        $('.ts-dropdown').css('font-size', '1rem');
    });
</script>
@endpush