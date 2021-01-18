@extends('layouts.admin', ['title' => 'Add Job'])

@section('content')

    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">

        @include('partials.messages')
        <form action="{{ route('jobs.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="">Job Title</label>
                <input type="text" name="title" class="form-control w-75" value="{{ old('title') }}">
            </div>
            <livewire:jobs.form-input />
            <hr>
            <button type="submit" class="btn  btn-primary float-right">Submit</button>

        </form>
    </div>

@endsection

