@extends('layouts.admin', ['title' => 'Edit Job'])

@section('content')

    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">

        @include('partials.messages')
        <form action="{{ route('jobs.update', $job->id)}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="">Edit Title</label>
                <input type="text" name="title" class="form-control w-75" value="{{ $job->title }}">
            </div>

            <button type="submit" class="btn  btn-primary">Update</button>

        </form>
    </div>

@endsection
