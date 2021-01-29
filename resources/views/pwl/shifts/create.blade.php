@extends('layouts.admin', ['title' => 'New Shift'])

@section('content')
    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">
        <h2 class="text-info text-center">New Shift</h2>
        @if($job)
            <livewire:shifts.shift-form :job="$job" />
        @endif
    </div>
@endsection
