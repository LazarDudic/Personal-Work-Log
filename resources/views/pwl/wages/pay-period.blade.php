@extends('layouts.admin', ['title' => 'Pay Period'])

@section('content')

<div class="m-auto col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="card shadow mb-4">
        <div class="card-header py-4 d-flex justify-content-between">
            <div><h5 class="m-0 font-weight-bold text-primary">Total</h5></div>
                <div>
                    @include('partials.messages')
                    <form action="{{ route('wages.pay-period', $job->id) }}" method="POST">
                        @csrf
                        <div class="d-xl-flex d-lg-flex">
                            <select name="pay_period" id="" class="form-control mb-1">
                                <option selected>Current</option>
                                @if($payPeriodDates)
                                    @foreach($payPeriodDates as $dates)
                                    <option value="{{ json_encode($dates) }}"
                                    {{ request('pay_period') == json_encode($dates) ? 'selected' : ''}}
                                    >
                                        {{ date('d-M-y', strtotime($dates['started_at'])) }}
                                        {{ ' / ' }}
                                        {{ date('d-M-y', strtotime($dates['finished_at'])) }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                            <button class="btn btn-info ml-2 float-right">History</button>
                        </div>

                    </form>
                </div>
        </div>

        <div class="card-body">
            <div class="table-responsive align-middle text-center">
                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="align-middle">Working Hours</th>
                        <th class="align-middle">Break Hours</th>
                        @if($job->tracking->wage)
                            <th class="align-middle">Total Pay</th>
                            <th class="align-middle">Regular Pay</th>

                            @if($job->tracking->overtime)
                                <th class="align-middle">Overtime Pay</th>
                                <th class="align-middle">Overtime Hours</th>
                            @endif

                            @if($job->tracking->shift_differential)
                                <th class="align-middle">Differential Pay</th>
                                <th class="align-middle">Differential Hours</th>
                            @endif

                            @if($job->tracking->tips)
                                <th class="align-middle">Tips</th>
                            @endif

                            @if($job->tracking->bonuses)
                                <th class="align-middle">Bonuses</th>
                            @endif
                            @if($job->tracking->expenses)
                                <th class="align-middle">Expenses</th>
                            @endif
                        @endif

                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td class="align-middle">{{ $total['total_working_minutes'] }}</td>
                        <td class="align-middle">{{ $total['break_minutes'] }}</td>
                        @if($job->tracking->wage)
                            <td class="align-middle">${{ $total['total_earnings']  }}</td>
                            <td class="align-middle">${{ $total['regular_earnings'] }}</td>
                            @if($job->tracking->overtime)
                                <td class="align-middle">${{ $total['overtime_earnings'] }}</td>
                                <td class="align-middle">{{ $total['overtime_minutes'] }}</td>
                            @endif
                            @if($job->tracking->shift_differential)
                                <td class="align-middle">${{ $total['shift_differential_earnings'] }}</td>
                                <td class="align-middle">{{ $total['shift_differential_minutes'] }}</td>
                            @endif
                            @if($job->tracking->tips)
                                <td class="align-middle">${{ $total['tips']}}</td>
                            @endif
                            @if($job->tracking->bonuses)
                                <td class="align-middle">${{ $total['bonuses'] }}</td>
                            @endif
                            @if($job->tracking->expenses)
                                <td class="align-middle">${{ $total['expenses'] }}</td>
                            @endif

                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-4 d-flex justify-content-between">
            <h5 class="m-0 font-weight-bold text-primary">Pay Period Shifts</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive align-middle text-center">
                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="align-middle">Started At</th>
                        <th class="align-middle">Finished At</th>
                        <th class="align-middle">Working Hours</th>
                        <th class="align-middle">Break Hours</th>
                        @if($job->tracking->wage)
                            <th class="align-middle">Total Pay</th>
                            <th class="align-middle">Regular Pay</th>

                            @if($job->tracking->overtime)
                                <th class="align-middle">Overtime Pay</th>
                                <th class="align-middle">Overtime Hours</th>
                            @endif

                            @if($job->tracking->shift_differential)
                                <th class="align-middle">Differential Pay</th>
                                <th class="align-middle">Differential Hours</th>
                            @endif

                            @if($job->tracking->tips)
                                <th class="align-middle">Tips</th>
                            @endif

                            @if($job->tracking->bonuses)
                                <th class="align-middle">Bonuses</th>
                            @endif
                            @if($job->tracking->expenses)
                                <th class="align-middle">Expenses</th>
                            @endif
                        @endif

                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th class="align-middle">Started At</th>
                        <th class="align-middle">Finished At</th>
                        <th class="align-middle">Working Hours</th>
                        <th class="align-middle">Break Hours</th>
                        @if($job->tracking->wage)
                            <th class="align-middle">Total Pay</th>
                            <th class="align-middle">Regular Pay</th>

                            @if($job->tracking->overtime)
                                <th class="align-middle">Overtime Pay</th>
                                <th class="align-middle">Overtime Hours</th>
                            @endif

                            @if($job->tracking->shift_differential)
                                <th class="align-middle">Differential Pay</th>
                                <th class="align-middle">Differential Hours</th>
                            @endif

                            @if($job->tracking->tips)
                                <th class="align-middle">Tips</th>
                            @endif

                            @if($job->tracking->bonuses)
                                <th class="align-middle">Bonuses</th>
                            @endif
                            @if($job->tracking->expenses)
                                <th class="align-middle">Expenses</th>
                            @endif
                        @endif
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($shifts as $shift)
                        <tr>
                            <td class="align-middle">{{ date('d-M-y H:i', strtotime($shift->started_at))  }}</td>
                            <td class="align-middle">{{ date('d-M H:i', strtotime($shift->finished_at)) }}</td>
                            <td class="align-middle">{{ convert_minutes_to_hours($shift->total_working_minutes) }}</td>
                            <td class="align-middle">{{ convert_minutes_to_hours($shift->break_minutes) }}</td>
                            @if($job->tracking->wage)
                                <td class="align-middle">{{ $shift->total_earnings ? '$'.$shift->total_earnings : '-' }}</td>
                                <td class="align-middle">{{ $shift->regular_earnings ? '$'.$shift->regular_earnings : '-'}}</td>
                                @if($job->tracking->overtime)
                                    <td class="align-middle">{{ $shift->overtime_earnings ? '$'.$shift->overtime_earnings : '-' }}</td>
                                    <td class="align-middle">{{ convert_minutes_to_hours($shift->overtime_minutes) }}</td>
                                @endif
                                @if($job->tracking->shift_differential)
                                    <td class="align-middle">
                                        {{ $shift->shift_differential_earnings ? '$'.$shift->shift_differential_earnings : '-' }}
                                    </td>
                                    <td class="align-middle">{{ convert_minutes_to_hours($shift->shift_differential_minutes) }}</td>
                                @endif
                                @if($job->tracking->tips)
                                    <td class="align-middle">{{ $shift->tips ? '$'.$shift->tips : '-' }}</td>
                                @endif
                                @if($job->tracking->bonuses)
                                    <td class="align-middle">{{ $shift->bonuses ?? '-' }}</td>
                                @endif
                                @if($job->tracking->expenses)
                                    <td class="align-middle">{{ $shift->expenses ?? '-' }}</td>
                                @endif

                            @endif
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


