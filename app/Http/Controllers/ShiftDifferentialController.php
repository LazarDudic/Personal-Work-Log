<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\UpdateShiftDifferentialRequest;
use App\Models\Job;
use App\Models\ShiftDifferential;

class ShiftDifferentialController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Job $job)
    {
        $shiftDifferential = ShiftDifferential::where('job_id', $job->id)->firstOrFail();

        return view('pwl.shift_differentials.edit', [
            'shiftDifferential' => $shiftDifferential,
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateShiftDifferentialRequest $request, Job $job)
    {
        if ($request->currency_amount && $request->percentage) {
            return back()->withErrors('You can only chose one field between currency amount and percentage.');
        }
        $job->tracking->update(['shift_differential' => $request->shift_differential]);

        $job->shiftDifferential->update($request->validated());

        return back()->withSuccess('Shift Differential updated successfully.');

    }

}
