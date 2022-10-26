@extends('super_admin.layouts.app')

@section('content')
<h3 class="text-dark mb-1">User Manual List</h3>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="tableManual">
                <caption></caption>
                <thead class="table-primary">
                    <tr>
                        <th>Manual Pengguna</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($manuals as $manual)
                        <tr>
                            <td><a href="{{route('super_admin.system.manual.view', ['id' => $manual->id ])}}">{{ $manual->name }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
