@extends('layouts.admin', ['title' => 'Edit Shift Differential'])

@section('content')

    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">


        <form action="{{ route('shift-differentials.update', $job->id) }}" method="POST">
            @csrf
            @method('PATCH')
            @include('partials.messages')

            <hr><h4 class="text-info">Shift Differential</h4>
            <div class="form-group d-lg-flex justify-content-between">
                <div class="custom-control custom-checkbox align-self-center">
                    <input type="checkbox" name="shift_differential" class="custom-control-input"
                           id="shiftDifferential" {{ $job->tracking->shift_differential ? 'checked' : '' }}>
                    <label class="custom-control-label" for="shiftDifferential">
                        <span class="badge">Keep track of shift differential</span>
                    </label>
                </div>
            </div>
            <div class="form-group d-flex">
                <div class="mr-2">
                    <span class="badge">Starts At</span>
                    <input  type="time" name="start_at" class="form-control" value="{{ $shiftDifferential->start_at }}">
                </div>
                <div>
                    <span class="badge">Ends At</span>
                    <input type="time" name="finish_at" class="form-control" value="{{ $shiftDifferential->finish_at }}">
                </div>
            </div>
            <div class="form-group d-flex">
                <span class="badge align-self-end pb-3">Increase By</span>
                <div class="mr-xl-2">
                    <span class="badge">Currency Amount</span>
                    <input name="currency_amount" class="form-control" type="number" step="any"
                           value="{{ $shiftDifferential->currency_amount }}">
                </div>
                <div>
                    <span class="badge">Percentage</span>
                    <input name="percentage" class="form-control" type="number"
                           value="{{ $shiftDifferential->percentage }}">
                </div>
            </div>
            <div class="form-group">
                <h5><span class="badge">Whole Days Differential</span></h5>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]"  value="1" class="custom-control-input"
                           id="monday" {{ differential_day_checked(1, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="monday">
                        <span class="badge">Monday</span>
                    </label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]" value="2" class="custom-control-input"
                           id="tuesday" {{ differential_day_checked(2, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="tuesday">
                        <span class="badge">Tuesday</span>
                    </label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]" value="3" class="custom-control-input"
                           id="wednesday" {{ differential_day_checked(3, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="wednesday">
                        <span class="badge">Wednesday</span>
                    </label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]" value="4" class="custom-control-input"
                           id="thursday" {{ differential_day_checked(4, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="thursday">
                        <span class="badge">Thursday</span>
                    </label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]" value="5" class="custom-control-input"
                           id="friday" {{ differential_day_checked(5, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="friday">
                        <span class="badge">Friday</span>
                    </label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]" value="6" class="custom-control-input"
                           id="saturday" {{ differential_day_checked(6, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="saturday">
                        <span class="badge">Saturday</span>
                    </label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="differential_days[]" value="7" class="custom-control-input"
                           id="sunday" {{ differential_day_checked(7, $shiftDifferential->differential_days) }}>
                    <label class="custom-control-label" for="sunday">
                        <span class="badge">Sunday</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn  btn-primary float-right">Submit</button>

        </form>
    </div>

@endsection
