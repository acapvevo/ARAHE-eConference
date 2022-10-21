<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>eConference</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('lib/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="{{asset('lib/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @yield('styles')
</head>

@yield('root')
    </div>
    <script src="{{asset('lib/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('lib/chart.js/dist/chart.min.js')}}"></script>
    <script src="{{asset('lib/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/theme.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>
    @yield('scripts')
</body>

</html>
