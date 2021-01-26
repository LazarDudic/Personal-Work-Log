@extends('layouts.admin', ['title' => 'Edit Time Zone'])


@section('content')
    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">


        <form action="{{ route('users.update-timezone') }}" method="POST">
            @csrf
            @method('PATCH')
            @include('partials.messages')

            <h4 class="text-info ">Time Zone</h4>
            <hr>

            <div class="form-group">
                <select class="form-control {{ $errors->has('timezone') ? 'is-invalid' : '' }}" name="timezone">
                    @foreach(timezone_identifiers_list() as $timezone)
                        <option {{ auth()->user()->timezone == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                    @endforeach
                </select>
            </div>



            <button type="submit" class="btn  btn-primary">Submit</button>

        </form>
    </div>

@endsection
