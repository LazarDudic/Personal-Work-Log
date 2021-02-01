<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\UpdateWageRequest;
use App\Models\Job;
use App\Models\Shift;
use App\Models\Wage;
use App\Services\PayPeriodHistory;
use App\Services\WorkLogCalculator;
use http\Env\Response;

/**
 * Class WageController
 * @package App\Http\Controllers
 */
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
     * @return \Illuminate\Contracts\View\View
     */
    public function getPayPeriod(Job $job)
    {
        $shifts = $this->getPayPeriodShifts($job);

        $calculator = new WorkLogCalculator($shifts);
        $total = $calculator->calculateHoursAndPay();
        $payPeriodHistory = new PayPeriodHistory($job->wage);
        $payPeriodDates = array_reverse($payPeriodHistory->getDates());

        return view('pwl.wages.pay-period', [
            'total' => $total,
            'shifts' => $shifts,
            'job' => $job,
            'payPeriodDates' => $payPeriodDates
        ]);
    }


    /**
     * @param Job $job
     * @return mixed
     */
    private function getPayPeriodShifts(Job $job)
    {
        if (\request()->has('pay_period') && \request()->get('pay_period') !== 'Current') {

            $requestedPayPeriod = $this->validateRequestedPayPeriod();
            session()->put('selected', $requestedPayPeriod);

            return Shift::where('job_id', $job->id)
                ->whereBetween('started_at', array_values($requestedPayPeriod))
                ->get();
        }

        session()->put('selected', null);

        return Shift::currentPayPeriod($job)->orderByDesc('started_at')->get();
    }

    /**
     * @return mixed
     */
    private function validateRequestedPayPeriod(): array
    {
        $requestedPayPeriod = json_decode(request()->pay_period, true);

        abort_if($requestedPayPeriod == null, 404);
        abort_if((bool)array_intersect(['started_at', 'finished_at'], array_keys($requestedPayPeriod)) == false, 404);
        abort_if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $requestedPayPeriod['started_at']) == 0, 404);
        abort_if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $requestedPayPeriod['finished_at']) == 0, 404);

        return $requestedPayPeriod;
    }
}
