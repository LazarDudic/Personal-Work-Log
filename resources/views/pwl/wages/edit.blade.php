@extends('layouts.admin', ['title' => 'Edit Wage'])

@section('content')

    <div class="m-auto col-xl-5 col-lg-8 col-md-12 col-sm-12">


        <form action="{{ route('wages.update', $job->id) }}" method="POST">
            @csrf
            @method('PATCH')
            @include('partials.messages')

            <hr><h4 class="text-info">Wage</h4>
            <div class="form-group d-lg-flex justify-content-between">
                <div class="custom-control custom-checkbox align-self-center">
                    <input type="checkbox" name="wage" class="custom-control-input"
                           id="wage" {{ $job->tracking->wage ? 'checked' : '' }}>
                    <label class="custom-control-label" for="wage">
                        <span class="badge">Keep track of wage</span>
                    </label>
                </div>
                <div class="d-lg-flex justify-content-center">
                    <span class="badge align-self-center">Hourly Rate</span>
                    <input type="number" name="hourly_rate" class="form-control w-50" min="1" step="any"
                           value="{{ $wage->hourly_rate }}">
                </div>
            </div>
            <div class="form-group d-lg-flex align-items-center">
                <span class="badge">Pay Period</span>
                <input type="number" name="time_length" class="form-control w-50 mr-2"
                       min="1" value="{{ $wage->time_length }}">
                <select name="pay_period" class="custom-select custom-select">
                    <option></option>
                    <option value="week" {{ $wage->pay_period == 'week' ? 'selected' : '' }}>
                        Week(s)
                    </option>
                    <option value="month" {{ $wage->pay_period == 'month' ? 'selected' : '' }}>
                        Month(s)
                    </option>
                    <option value="twiceEveryMonth" {{ $wage->pay_period == 'twiceEveryMonth' ? 'selected' : '' }}>
                        1-15, 16-end
                    </option>
                </select>
            </div>

            <button type="submit" class="btn  btn-primary float-right">Submit</button>

        </form>
    </div>

@endsection
