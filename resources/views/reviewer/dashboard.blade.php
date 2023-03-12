@extends('reviewer.layouts.app')

@section('content')
    <h3 class="text-dark mb-1">Dashboard</h3>

    <div class="card">
        <h4 class="card-header text-center">ARAHE{{ $form->session->year }}</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-secondary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-secondary fw-bold text-xs mb-1"><span>Paper Assigned</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0">
                                        <span>{{ $record->assign }}
                                            Papers</span>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fa-solid fa-file-circle-plus fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-danger py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-danger fw-bold text-xs mb-1"><span>Paper Rejected</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0">
                                        <span>{{ $record->reject }}
                                            Papers</span>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fa-solid fa-file-circle-xmark fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-warning py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>Paper Returned</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $record->return }}
                                        Papers</span></div>
                                </div>
                                <div class="col-auto"><i class="fa-solid fa-file-circle-minus fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Paper Accepted</span>
                                    </div>
                                    <div class="text-dark fw-bold h5 mb-0">
                                        <span>{{ $record->accept }}
                                            Papers</span>
                                    </div>
                                </div>
                                <div class="col-auto"><i class="fa-solid fa-file-circle-check fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 pb-3 row">
                <div class="col-lg-8 table-responsive">
                    <div class="card m-3">
                        <h4 class="card-header text-center">Papers need Review</h4>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Paper Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($submissions as $index => $submission)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$submission->title}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <th colspan="3" class="text-center">No Paper</th>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
