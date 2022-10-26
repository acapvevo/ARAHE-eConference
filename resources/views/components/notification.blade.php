<div class='notifications'></div>


<script>
    @if (Session::has('success'))
        Swal.fire(
            'Success',
            "{{ session('success') }}",
            'success'
        );
        @php
            Session::forget('success');
        @endphp
    @endif


    @if (Session::has('error'))
        Swal.fire(
            'Error',
            "{{ session('error') }}",
            'error'
        );
        @php
            Session::forget('error');
        @endphp
    @endif

    @if ($errors->any())
        Swal.fire(
            'Form Validation Error',
            "Please Check Your Input",
            'error'
        );
    @endif


    @if (Session::has('info'))
        Swal.fire(
            'Information',
            "{{ session('info') }}",
            'info'
        );
        @php
            Session::forget('info');
        @endphp
    @endif


    @if (Session::has('warning'))
        Swal.fire(
            'Warning',
            "{{ session('warning') }}",
            'warning'
        );
        @php
            Session::forget('warning');
        @endphp
    @endif

    function confirmation(question, then){
        Swal.fire(
            'Confirmation',
            question,
            'question'
        ).then(then);
    }
</script>
