@extends('participant.layouts.app')

@section('content')
<h3 class="text-dark mb-1">{{$manual->name }}</h3>

<div class="card">
    <div class="card-body text-center">
        @if (isset($manual->file))
            <iframe src="{{ route('participant.system.manual.stream', ['id' => $manual->id]) }}"
                class="w-100 pt-3 pb-3" height="750">
            </iframe>
        @else
            <h3>No User Manual Uploaded for {{ $manual->name }}</h3>
        @endif
    </div>
</div>
@endsection
