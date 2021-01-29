@extends('layouts.admin', ['title' => 'Tracking'])

@section('content')
    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">
        <form action="{{ route('tracking.update', $job->id) }}" method="POST">
            @csrf
            @method('PATCH')
            @include('partials.messages')
            <h2 class="text-info text-center">Tracking</h2>
            <hr>

            <div class="custom-control custom-checkbox">
                <input type="hidden" name="tips" value="0">
                <input type="checkbox" name="tips" class="custom-control-input"
                       id="tips" value="1" {{ $tracking->tips ? 'checked': '' }}>
                <label class="custom-control-label" for="tips">
                    <span class="badge">Keep track of tips</span>
                </label>
            </div>
            <hr>

            <div class="custom-control custom-checkbox">
                <input type="hidden" name="bonuses" value="0">
                <input type="checkbox" name="bonuses" class="custom-control-input"
                       id="bonuses" value="1" {{ $tracking->bonuses ? 'checked': '' }}>
                <label class="custom-control-label" for="bonuses">
                    <span class="badge">Keep track of bonuses</span>
                </label>
            </div>
            <hr>
            <div class="custom-control custom-checkbox">
                <input type="hidden" name="expenses" value="0">
                <input type="checkbox" name="expenses" class="custom-control-input"
                       id="expenses" value="1" {{ $tracking->bonuses ? 'checked': '' }}>
                <label class="custom-control-label" for="expenses">
                    <span class="badge">Keep Track of expenses</span>
                </label>
            </div>
            <hr>
            <button type="submit" class="btn  btn-primary mt-3">Save</button>
        </form>
    </div>

@endsection
