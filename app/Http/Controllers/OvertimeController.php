<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\UpdateOvertimeRequest;
use App\Models\Job;
use App\Models\Overtime;

class OvertimeController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Job $job)
    {
        $overtime = Overtime::where('job_id', $job->id)->firstOrFail();

        return view('pwl.overtime.edit', [
            'overtime' => $overtime,
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOvertimeRequest $request
     * @param Job $job
     * @return void
     */
    public function update(UpdateOvertimeRequest $request, Job $job)
    {
        $job->tracking->update(['overtime' => $request->overtime]);

        $job->overtime()->update($request->only('overtime_pay', 'starting_hour', 'calculated_by'));

        return back()->withSuccess('Overtime updated successfully.');
    }

}
