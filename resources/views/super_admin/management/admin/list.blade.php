@extends('super_admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Admin Management</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                        Add Admin
                    </button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger" form="deleteAdminForm">
                        Delete Admin
                    </button>
                </div>
            </div>
            <form action="{{ route('super_admin.management.admin.delete') }}" method="post" id="deleteAdminForm">
                @csrf
                @method('DELETE')

                <div class="table-responsive">
                    <table class="table table-bordered" id="tableAdmin">
                        <caption></caption>
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 5%">
                                    <div class="checkbox"><input type="checkbox" id="checkAll"></div>
                                </th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>
                                        <div class="checkbox"><input class="checkboxTick" type="checkbox" name="admin_ids[]"
                                                value="{{ $admin->id }}"></div>
                                    </td>
                                    <td><a
                                            href="{{ route('super_admin.management.admin.view', ['id' => $admin->id]) }}">{{ $admin->name }}</a>
                                    </td>
                                    <td>{{ $admin->username }}</td>
                                    <td>{{ $admin->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createAdminModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('super_admin.management.admin.create') }}" method="post" id="createAdminForm">
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
                    <button type="submit" class="btn btn-primary" form="createAdminForm">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableAdmin').DataTable();

            $('#checkAll').click(function() {
                var checked = this.checked;
                $('input[class="checkboxTick"]').each(function() {
                    this.checked = checked;
                });
            });
        });
    </script>
@endsection
