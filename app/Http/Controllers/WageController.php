<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\UpdateWageRequest;
use App\Models\Job;
use App\Models\Shift;
use App\Models\Wage;
use App\Services\WorkLogCalculator;

class WageController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Job $job)
    {
        $wage = Wage::where('job_id', $job->id)->firstOrFail();

        return view('pwl.wages.edit', [
            'wage' => $wage,
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWageRequest $request
     * @param Job $job
     * @return void
     */
    public function update(UpdateWageRequest $request, Job $job)
    {
        $job->tracking->update(['wage' => $request->wage]);

        if ($request->pay_period == 'twiceEveryMonth') {
            $request->merge([
                'time_length' => null,
                'pay_period_start_at' => null
            ]);
        }

        $job->wage()->update($request->only('hourly_rate', 'time_length', 'pay_period', 'pay_period_start_at'));

        return back()->withSuccess('Wage updated successfully.');
    }

    /**
     * @param Job $job
     */
    public function getPayPeriod(Job $job)
    {
        $shifts = Shift::currentPayPeriod($job)->orderByDesc('started_at')->get();

        $calculator = new WorkLogCalculator($shifts);

        $total = $calculator->calculateHoursAndPay();

        return view('pwl.wages.pay-period', [
            'total' => $total,
            'shifts' => $shifts,
            'job' => $job
        ]);
    }




}
