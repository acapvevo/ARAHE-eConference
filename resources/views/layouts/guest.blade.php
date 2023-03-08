@extends('layouts.base')

@section('root')
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
