<?php

namespace App\Http\Livewire\Shifts;

use Carbon\Carbon;
use Livewire\Component;

class ShiftClock extends Component
{
    public $clockedIn = false;
    public $clockedOut = false;
    public $shiftStarted;

    public $break = false;
    public $breakTotal = 0;
    public $breakStarted = null;

    public function mount()
    {
        if (session()->has('shiftStarted')) {
            $this->clockedIn = true;
            $this->shiftStarted = session()->get('shiftStarted');
            $this->break = session()->get('break') ?? false;
            $this->breakStarted = session()->get('breakStarted');
        }
    }

    public function cancel()
    {
        $this->reset(['clockedIn', 'shiftStarted', 'break', 'breakStarted']);

        session()->forget('shiftStarted');
        session()->forget('break');
        session()->forget('breakStarted');
        session()->forget('oldBreakTotal');
    }

    public function clockIn()
    {
        $this->clockedIn = true;

        $this->shiftStarted = $this->now();
        session(['shiftStarted' => $this->shiftStarted]);
    }

    public function clockOut()
    {
        $this->clockedOut = true;
        $this->emitUp(
            'clockedOut',
            $this->shiftStarted->format('Y-m-d\TH:i'),
            $this->now()->format('Y-m-d\TH:i'),
            $this->getBreakTimeInMinutes()
        );
    }



    /**
     * Live break count
     */
    public function breakTotal()
    {
        if ($this->break == false) {
            $this->breakTotal = session()->get('oldBreakTotal');
        } else {
            $oldBreakTotal = session()->get('oldBreakTotal');
            $this->breakTotal = ($this->now()->timestamp - $this->breakStarted->timestamp) + $oldBreakTotal;
        }
    }

    public function breakStart()
    {
        $this->breakStarted = $this->now();
        session(['breakStarted' => $this->breakStarted]);

        $this->break = true;
        session(['break' => true]);
    }

    public function breakPause()
    {
        $this->break = false;
        $this->breakStarted = null;

        session()->forget('break');
        session()->forget('breakStarted');
        session(['oldBreakTotal' => $this->breakTotal]);
    }

    public function getBreakTimeInMinutes()
    {
        return round($this->breakTotal / 60);
    }

    private function now()
    {
        return Carbon::now(auth()->user()->timezone);
    }

    public function render()
    {
        return view('livewire.shifts.shift-clock');
    }
}
