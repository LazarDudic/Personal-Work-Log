<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\SearchShiftRequest;
use App\Models\Job;
use App\Models\Shift;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Job $job
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Job $job)
    {
        $shifts = Shift::where('job_id', $job->id)->paginate(10);

        return view('pwl.shifts.index', [
            'shifts' => $shifts,
            'job' => $job
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $job = Job::where('current_job', 1)->first();

        return view('pwl.shifts.create', [
            'job' => $job
        ]);
    }

    public function search(SearchShiftRequest $request, Job $job)
    {
        if ($request->isMethod('post')) {
            $shifts = Shift::where('job_id', $job->id)
                ->whereBetween('started_at', array_values($request->validated()))
                ->paginate(10);
        }   else {
            $shifts = Shift::where('job_id', $job->id)->paginate(10);
        }


        return view('pwl.shifts.index', [
            'shifts' => $shifts,
            'job' => $job
        ]);
    }



}
