<?php

namespace App\Http\Livewire\Shifts;

use App\Models\Job;
use App\Models\Shift;
use Carbon\Carbon;
use Livewire\Component;

class ShiftForm extends Component
{
    public Job $job;
    public $clockedOut = false;
    protected $listeners = ['clockedOut'];

    public $started_at;
    public $finished_at;
    public $break_minutes;
    public $tips;
    public $bonuses;
    public $expenses;

    protected $rules = [
        'started_at'    => ['required','date'],
        'finished_at'   => ['required','date', 'after:started_at'],
        'break_minutes' => ['required','integer'],
        'tips'          => ['nullable','numeric'],
        'bonuses'       => ['nullable','numeric'],
        'expenses'      => ['nullable','numeric'],
    ];

    protected $messages = [
        'started_at.required'  => 'The Shift Start cannot be empty.',
        'finished_at.required' => 'The Shift End cannot be empty.',
        'finished_at.after'    => 'The Shift End must be after Shift Start.',
    ];

    public function saveShift()
    {
        $validateData = $this->validate();

        if ($this->shiftMustBeMax24H() > 1440) {
            return $this->addError('finished_at', 'Shift can not be longer than 24h.');
        }

        if ($this->shiftOverlap() === true) {
            return $this->addError('finished_at', 'Cannot have two shifts at the same time.');
        }

        $validateData['job_id'] = $this->job->id;

        $shift = Shift::create($validateData);
        $shift->calculate($this->job);

        session()->flash('success', 'Shift saved successfully.');
        return redirect()->to(route('shifts.index', $this->job->id));
    }

    public function clockedOut($shiftStarted, $shiftEnded, $totalBreak)
    {
        $this->started_at  = $shiftStarted;
        $this->finished_at = $shiftEnded;
        $this->break_minutes  = $totalBreak;
        $this->clockedOut  = true;
    }

    public function shiftMustBeMax24H()
    {
        $started = Carbon::parse($this->started_at);
        $finished = Carbon::parse($this->finished_at);

        return $started->diffInMinutes($finished);
    }

    private function shiftOverlap()
    {
        return Shift::where('finished_at', '>', $this->started_at)->count() > 0;
    }

    public function render()
    {
        return view('livewire.shifts.shift-form');
    }
}
