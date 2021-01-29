<?php

namespace App\Traits;

use App\Models\Job;
use Carbon\Carbon;


trait ShiftCalculation
{
    private $job;
    private $data = [];

    public function calculate(Job $job)
    {
        $this->job = $job;

        if ($job->tracking->wage) {
            $this->data['total_earnings'] = 0;

            if ($job->tracking->overtime) {
                $this->countOvertime();
            }

            if ($job->tracking->shift_differential) {
                $this->countShiftDifferential();
            }

            $this->countRegularEarnings();
        }

        $this->data['total_working_minutes'] = $this->getWorkingMinutes();
        $this->update($this->data);
    }

    public function countShiftDifferential()
    {
        // Whole day is Differential
        if ($this->job->shiftDifferential->differential_days &&
            in_array(Carbon::now()->dayOfWeek, $this->job->shiftDifferential->differential_days))
        {
            $workingMinutes = $this->getWorkingMinutes();
            $data['shift_differential_minutes'] = Carbon::parse($workingMinutes * 60)->format('H:i');

            $this->countDifferentialPercentageAmount($workingMinutes);
            $this->countDifferentialCurrencyAmount($workingMinutes);

            $this->data['shift_differential_minutes'] = $workingMinutes;
            $this->data['total_earnings'] += $this->data['shift_differential_earnings'];
            return;
        }

        // Shift Differential starts at certain time
        if ($this->job->shiftDifferential->start_at) {
            $differentialStarts = Carbon::parse($this->job->shiftDifferential->start_at);
            $differentialEnds = Carbon::parse($this->job->shiftDifferential->finish_at);

            $startedAt = Carbon::parse($this->started_at);
            $finishedAt = Carbon::parse($this->finished_at);

            // Midnight Differential
            if ($differentialStarts > $differentialEnds) {
                // Db differentials doest have date only time, so they use current date(today) from carbon and db time
                // Adding 1 day means differential ends tomorrow not today
                $differentialEnds->addDay();

                // Midnight shift
                if ($finishedAt < $startedAt) {

                    // Shift started and finished during diff time
                    if ($startedAt >= $differentialStarts && $finishedAt <= $differentialEnds ) {
                        $this->data['shift_differential_minutes'] = $startedAt->diffInMinutes($finishedAt);
                    // Shift started before and finished after diff time
                    } elseif ($startedAt <= $differentialStarts && $finishedAt >= $differentialEnds ) {
                        $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($differentialEnds);
                    // Shift started during and finished after diff time
                    } elseif ($startedAt >= $differentialStarts && $finishedAt >= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $startedAt->diffInMinutes($differentialEnds);
                    // Shift started before and finished during diff time
                    } elseif($startedAt <= $differentialStarts && $finishedAt <= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($finishedAt);
                    }
                } else { // Shift doesn't have midnight
                    // Shift started and finished during diff time
                    if ($finishedAt <= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $startedAt->diffInMinutes($finishedAt);
                    // Shift started during and finished after diff time
                    } elseif ($startedAt <= $differentialEnds && $finishedAt >= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $startedAt->diffInMinutes($differentialEnds);
                    // Shift finished without caching diff time
                    } elseif ($startedAt >= $differentialEnds && $finishedAt >= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = null;
                    }
                }

            } else { // Differential doesn't have midnight
                // Midnight shift
                if ($finishedAt < $startedAt) {
                    $finishedAt->addDay();
                    // Shift started and ended before diff start
                    if ($startedAt >= $differentialEnds && $finishedAt <= $differentialStarts) {
                        $this->data['shift_differential_minutes'] = null;
                        //Shift started during diff time
                    } elseif ($startedAt >= $differentialEnds && $finishedAt >= $differentialStarts) {
                        // Shift finished during diff time
                        if ($finishedAt >= $differentialEnds) {
                            $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($differentialEnds);
                        } else { // Shift finished after diff end
                            $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($finishedAt);
                        }
                        // Shift started before diff time
                    } elseif ($startedAt <= $differentialStarts) {
                        $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($differentialEnds);
                    }
                } else { // Shift doesn't have midnight
                    // Shift started before and finished during diff time
                    if ($startedAt <= $differentialStarts && $finishedAt <= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($finishedAt);
                    // Shift started before and finished after diff time
                    } elseif ($startedAt <= $differentialStarts && $finishedAt >= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $differentialStarts->diffInMinutes($differentialEnds);
                    // Shift started and finished during dif time
                    } elseif ($startedAt >= $differentialStarts && $finishedAt <= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $startedAt->diffInMinutes($finishedAt);
                    // Shift started during and finished after diff time
                    } elseif ($startedAt >= $differentialStarts && $finishedAt >= $differentialEnds) {
                        $this->data['shift_differential_minutes'] = $startedAt->diffInMinutes($differentialEnds);
                    } else { // Shifts out of diff time range
                        $this->data['shift_differential_minutes'] = null;
                    }
                }
            }

            $this->countDifferentialPercentageAmount($this->data['shift_differential_minutes']);
            $this->countDifferentialCurrencyAmount($this->data['shift_differential_minutes']);

            $this->data['total_earnings'] += $this->data['shift_differential_earnings'];
        }
    }

    public function countOvertime()
    {
        // Single Shift will only record overtime if it is calculated per shift
        if ($this->job->overtime->calculated_by == 'shift') {
            $workingMinutes = $this->getWorkingMinutes();
            $overtimeStartingMinute = $this->job->overtime->starting_hour * 60;

            if ($workingMinutes - $overtimeStartingMinute > 0) {
                $overtimeMinutes = $workingMinutes - $overtimeStartingMinute;
                $this->data['overtime_earnings'] = ($overtimeMinutes / 60) * $this->job->overtime->overtime_pay;
                $this->data['overtime_minutes'] = $overtimeMinutes;
                $this->data['total_earnings'] += $this->data['overtime_earnings'];

            }
        }
    }

    protected function countRegularEarnings()
    {
        $workingMinutes = $this->getWorkingMinutes();

        $this->data['regular_earnings'] = ($workingMinutes / 60) * $this->job->wage->hourly_rate;
        $this->data['total_earnings'] += $this->data['regular_earnings'];
    }


    private function getWorkingMinutes(): int
    {
        $startedAt = Carbon::parse($this->started_at);
        $finishedAt = Carbon::parse($this->finished_at);
        return $finishedAt->subMinutes($this->break_minutes)->diffInMinutes($startedAt);
    }

    /**
     * Shift Differential pay is increased by percentage
     * @param int $workingMinutes
     * @return false
     */
    private function countDifferentialPercentageAmount($workingMinutes)
    {
        if (! $this->job->shiftDifferential->percentage) {
            return false;
        }

        $amount = $this->job->wage->hourly_rate * ($this->job->shiftDifferential->percentage / 100);
        $this->data['shift_differential_earnings'] = ($workingMinutes / 60) * $amount;
    }


    /**
     * Shift Differential pay is increased by currency amount
     * @return false
     */
    private function countDifferentialCurrencyAmount($workingMinutes)
    {
        if (! $this->job->shiftDifferential->currency_amount) {
            return false;
        }

        $amount =  $this->job->shiftDifferential->currency_amount;
        $this->data['shift_differential_earnings'] = ($workingMinutes / 60) * $amount;
    }
}
