<?php

namespace App\Http\Controllers;

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
        $shifts = Shift::where('job_id', $job->id)->get();

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

}
