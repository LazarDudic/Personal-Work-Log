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
    public $newBreakStarted = null;
    public $breakPauseTotal = 0;
    public $breakPauseStarted = null;

    public function clockIn()
    {
        $this->clockedIn = true;

        $this->shiftStarted = $this->now();
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
        $countBrakes = $this->countBrakes();

        $this->breakTotal = $this->now()->timestamp - $countBrakes;
    }

    public function breakStart()
    {
        $this->breakStarted ??= $this->now();
        $this->newBreakStarted = $this->now();

        $this->break = true;
    }

    public function breakPause()
    {
        $this->break = false;
        $this->breakPauseStarted = $this->now();
        $this->newBreakStarted = null;
    }

    /**
     * Count total time of previous breaks
     */
    private function countBrakes()
    {
        $breakCount = $this->breakStarted->timestamp + $this->breakPauseTotal;

        if ($this->breakPauseStarted) {
            $this->breakPauseTotal += $this->now()->timestamp - $this->breakPauseStarted->timestamp;
            $breakCount = $this->breakStarted->timestamp + $this->breakPauseTotal;
            $this->breakPauseStarted = null;
        }

        return $breakCount;
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
