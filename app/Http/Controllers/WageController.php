<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\UpdateWageRequest;
use App\Models\Job;
use App\Models\Shift;
use App\Models\Wage;
use App\Services\PayPeriodHistory;
use App\Services\WorkLogCalculator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayPeriodExport;

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
     * @param $fileExtension
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Job $job, $fileExtension)
    {
        abort_if(! in_array($fileExtension, ['xlsx', 'pdf']), 404);

        $shifts = $this->getPayPeriodShifts($job);
        $calculator = new WorkLogCalculator($shifts);
        $total = $calculator->calculateHoursAndPay();

        $fileName = request('pay_period') . '.' . $fileExtension;
        $constant = $fileExtension === 'xlsx'
            ?  \Maatwebsite\Excel\Excel::XLSX
            :  \Maatwebsite\Excel\Excel::DOMPDF;

        return Excel::download(new PayPeriodExport($total), $fileName, $constant);
    }


    /**
     * @param Job $job
     * @return mixed
     */
    private function getPayPeriodShifts(Job $job)
    {
        if (\request()->has('pay_period') && \request()->get('pay_period') !== 'Current') {
            $requestedPayPeriod = $this->validateRequestedPayPeriod();

            return Shift::where('job_id', $job->id)
                ->whereBetween('started_at', array_values($requestedPayPeriod))
                ->get();
        }

        return Shift::currentPayPeriod($job)->get();
    }



    /**
     * @return mixed
     */
    private function validateRequestedPayPeriod(): array
    {
        $requestedPayPeriod = explode('_', request()->pay_period);

        abort_if(count($requestedPayPeriod) != 2, 404);
        abort_if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $requestedPayPeriod[0]) == 0, 404);
        abort_if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $requestedPayPeriod[1]) == 0, 404);

        return $requestedPayPeriod;
    }
}
