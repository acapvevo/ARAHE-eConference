@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Payment Management - Category List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 10%">Year</th>
                            <th>Name</th>
                            <th style="width: 10%">Code</th>
                            <th style="width: 15%">Standard Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{$category->form->session->year}}</td>
                                <td><a
                                        href="{{ route('admin.payment.category.view', ['id' => $category->id]) }}">{{ $category->name }}</a>
                                </td>
                                <td>{{$category->code}}</td>
                                <td>RM {{number_format($category->standardAmount, 2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
