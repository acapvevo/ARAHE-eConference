@extends('super_admin.layouts.app')

@section('content')
<h3 class="text-dark mb-1">System Health Check</h3>
<iframe  src="{{route('super_admin.system.health.main', ['fresh'])}}" width="100%" height="1000">Your browser isn't compatible</iframe>
@endsection
