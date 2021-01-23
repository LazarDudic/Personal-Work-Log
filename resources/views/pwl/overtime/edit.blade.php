@extends('layouts.admin', ['title' => 'Edit Overtime'])

@section('content')

    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">


        <form action="{{ route('overtime.update', $job->id) }}" method="POST">
            @csrf
            @method('PATCH')
            @include('partials.messages')

            <hr><h4 class="text-info">Overtime</h4>
            <div class="form-group d-lg-flex justify-content-between">
                <div class="custom-control custom-checkbox align-self-center">
                    <input type="checkbox" name="overtime" class="custom-control-input"
                           id="overtime" {{ $job->tracking->overtime ? 'checked' : '' }}>
                    <label class="custom-control-label" for="overtime">
                        <span class="badge">Keep track of overtime</span>
                    </label>
                </div>
                <div class="d-lg-flex justify-content-center">
                    <span class="badge align-self-center">Overtime Pay</span>
                    <input type="number" name="overtime_pay" class="form-control w-50"
                           min="0" step="any" value="{{ $overtime->overtime_pay }}">
                </div>
            </div>
            <div class="form-group d-lg-flex align-items-center">
                <span class="badge">Starting After</span>
                <input type="number" name="starting_hour" class="form-control w-50 mr-2"
                       min="0" placeholder="Hours" value="{{ $overtime->starting_hour }}">
                <select name="calculated_by" class="custom-select custom-select">
                    <option value="" selected disabled>Calculated By</option>
                    <option value="week" {{ $overtime->calculated_by == 'week' ? 'selected' : '' }}>
                        Week
                    </option>
                    <option value="payPeriod" {{ $overtime->calculated_by == 'payPeriod' ? 'selected' : '' }}>
                        Pay Period
                    </option>
                    <option value="shift" {{ $overtime->calculated_by == 'shift' ? 'selected' : '' }}>
                        Shift
                    </option>
                </select>
            </div>

            <button type="submit" class="btn  btn-primary float-right">Submit</button>

        </form>
    </div>

@endsection
