<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\UpdateTrackingRequest;
use App\Models\Job;
use App\Models\Tracking;

class TrackingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Job $job)
    {
        $tracking = Tracking::where('job_id', $job->id)->firstOrFail();

        return view('pwl.tracking.edit', [
            'tracking' => $tracking,
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTrackingRequest $request
     * @param Job $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateTrackingRequest $request, Job $job)
    {
        $job->tracking->update($request->validated());

        return back()->withSuccess('Tracking updated successfully.');

    }
}
