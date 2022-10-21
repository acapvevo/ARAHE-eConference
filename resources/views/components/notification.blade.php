


<div class='notifications'></div>


<script>
    @if (Session::has('success'))
        Swal.fire(
            'Berjaya',
            "{{ session('success') }}",
            'success'
        );
        @php
            Session::forget('success');
        @endphp
    @endif


    @if (Session::has('error'))
        Swal.fire(
            'Masalah',
            "{{ session('error') }}",
            'error'
        );
        @php
            Session::forget('error');
        @endphp
    @endif

    @if ($errors->any())
        Swal.fire(
            'Masalah Timbul Semasa Validasi Input',
            "Sila Periksa Input Semula",
            'error'
        );
    @endif


    @if (Session::has('info'))
        Swal.fire(
            'Maklumat',
            "{{ session('info') }}",
            'info'
        );
        @php
            Session::forget('info');
        @endphp
    @endif


    @if (Session::has('warning'))
        Swal.fire(
            'Amaran',
            "{{ session('warning') }}",
            'warning'
        );
        @php
            Session::forget('warning');
        @endphp
    @endif
</script>
