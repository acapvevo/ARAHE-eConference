@extends('layouts.base')

@section('root')
<body id="page-top">
    <div id="wrapper">
        @include('template.layouts.sidebar')
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                @include('template.layouts.navbar')
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
@endsection
