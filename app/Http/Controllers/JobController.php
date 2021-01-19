<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\CreateJobRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    /**
     * Display a listing of the jobs.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pwl.jobs.index', [
            'jobs' => Job::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('pwl.jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateJobRequest $request)
    {
        $job = Job::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title
        ]);

        $job->storeCharacteristics($request);

        if (Job::all()->count() == 1) {
            $job->current_job = 1;
            $job->save();
        }

        return redirect(route('jobs.index'))->withSuccess('Job added successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Job $job
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Job $job)
    {
        return view('pwl.jobs.edit', [
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $job->update($request->only('title'));

        return back()->withSuccess('Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Job $job
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Job $job)
    {
        if ($job->current_job && Job::all()->count() > 1) {
            return back()->withErrors('Please change your current job before delete.');
        }
        $job->delete();

        return back()->withSuccess('Job deleted successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Job $job
     * @return \Illuminate\Http\Response
     */
    public function addCurrentJob(Job $job)
    {
        abort_if($job->current_job == 1, Response::HTTP_FORBIDDEN);

        $currentJob = Job::where('current_job', 1)->first();
        $currentJob->current_job = 0;
        $currentJob->save();

        $job->current_job = 1;
        $job->save();

        return back()->withSuccess($job->title . ' is a new current job.');
    }


}
