<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\CreateJobRequest;
use App\Models\Job;
use Illuminate\Http\Request;

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
//        dd($request->all());
        $job = Job::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title
        ]);

        $job->storeCharacteristics($request);

        return redirect(route('jobs.index'))->withSuccess('Job added successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
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
     * @return void
     * @throws \Exception
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return back()->withSuccess('Job deleted successfully.');
    }
}
