@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Payment Management - Category Detail and Bill List</h3>

    <div class="card">
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-detail-tab" data-bs-toggle="tab" data-bs-target="#nav-detail"
                        type="button" role="tab" aria-controls="nav-detail" aria-selected="true">Detail</button>
                    <button class="nav-link" id="nav-bills-tab" data-bs-toggle="tab" data-bs-target="#nav-bills"
                        type="button" role="tab" aria-controls="nav-bills" aria-selected="false">Bills</button>
                </div>
            </nav>
            <div class="tab-content pt-3 pb-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab"
                    tabindex="0">

                    <div class="row pt-3 pb-3">
                        <div class="col">
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#updateCategoryModal">
                                Update Category
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="w-25">Year</th>
                                    <td>{{ $category->form->session->year }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Name</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Description</th>
                                    <td>{{ $category->description }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">ToyyibPay Code</th>
                                    <td>{{ $category->code }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Status</th>
                                    <td>{{ $infoToyyibpay->categoryStatus ? 'Active' : 'Inactive' }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Standard Amount</th>
                                    <td>RM {{ number_format($category->standardAmount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="nav-bills" role="tabpanel" aria-labelledby="nav-bills-tab" tabindex="0">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_id">
                            <thead class="table-primary">
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $submissions = $category->form->submissions->where('status_code', 'A');
                                @endphp
                                @foreach ($submissions as $submission)
                                @php
                                    $bill = $submission->bill;
                                @endphp
                                    <tr>
                                        <td><a
                                                href="{{ route('admin.payment.bill.view', $bill->id) }}">{{ $submission->participant->name }}</a>
                                        </td>
                                        <td>
                                            {{$bill->getPayAt()}}
                                        </td>
                                        <td>
                                            RM {{number_format($bill->amount, 2)}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="updateCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateCategoryModalLabel">Update Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('admin.payment.category.update', ['id' => $category->id]) }}" method="post"
                        id="updateCategory">
                        @csrf
                        @method('PATCH')

                        <label for="standardAmount" class="form-label">Standard Amount</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="standardAmountLabel">RM</span>
                            <input type="number" step="0.01"
                                class="form-control {{ $errors->has('standardAmount') ? 'is-invalid' : '' }}"
                                id="standardAmount" name="standardAmount" placeholder="Enter Standard Amount"
                                aria-label="Standard Amount" aria-describedby="standardAmountLabel">
                            @error('standardAmount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateCategory">Update</button>
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
            $('#table_id').DataTable();
        });
    </script>
@endsection
