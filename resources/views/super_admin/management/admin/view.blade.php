@extends('super_admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Admin Management - {{ $admin->name }}</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateAdminModal">
                        Update Admin
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="w-25">Name</td>
                        <td>{{ $admin->name }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">Username</td>
                        <td>{{ $admin->username }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">Email Address</td>
                        <td>{{ $admin->email }}</td>
                    </tr>
                    <tr>
                        <td class="w-25">Last Login</td>
                        <td>{{ $admin->getLoginAt() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateAdminModal" tabindex="-1" aria-labelledby="updateAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateAdminModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('super_admin.management.admin.create') }}" method="post" id="updateAdminForm">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                id="email" name="email" placeholder="Email Address" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateAdminForm">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
