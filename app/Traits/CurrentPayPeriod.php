<?php

namespace App\Traits;

use App\Models\Job;
use Carbon\Carbon;

trait CurrentPayPeriod
{
    private $job;
    private $timeZone;

    public function scopeCurrentPayPeriod($query, Job $job)
    {
        $this->job = $job;
        $this->timeZone = $job->user->timezone;

        if ($job->wage->pay_period == 'week') {
            $startedAt = $this->currentWeekPayPeriod();

            return static::where(function ($query) use ($startedAt) {
                $query->where('started_at', '>', $startedAt);
            });
        }

        if ($job->wage->pay_period == 'month') {
            $startedAt = $this->currentMonthPayPeriod();

            return static::where(function ($query) use ($startedAt) {
                $query->where('started_at', '>', $startedAt);
            });
        }

        if ($job->wage->pay_period == 'twiceEveryMonth') {
            $startedAt = $this->currentTwiceEveryMonthPayPeriod();

            return static::where(function ($query) use ($startedAt) {
                $query->where('started_at', '>', $startedAt);
            });
        }
    }

    private function currentWeekPayPeriod()
    {
        $time = $this->job->wage->time_lenght ?? 1;
        $startAt = Carbon::parse($this->job->wage->pay_period_start_at);

        $diffInDays = $startAt->diffInDays(Carbon::now($this->timeZone));

        $diffInDays = $diffInDays % (7 * $time);

        return Carbon::now($this->timeZone)->subDays($diffInDays)->format('Y-m-d');
    }

    private function currentMonthPayPeriod()
    {
        $time = $this->job->wage->time_lenght ?? 1;
        $startAt = Carbon::parse($this->job->wage->pay_period_start_at);

        $diffInMonths = $startAt->diffInMonths(Carbon::now($this->timeZone));

        $diffInDays = $startAt->addMonths($diffInMonths)
                              ->diffInDays(Carbon::now($this->timeZone));

        return Carbon::now($this->timeZone)->subDays($diffInDays)->format('Y-m-d');
    }

    private function currentTwiceEveryMonthPayPeriod()
    {
        $now = Carbon::now($this->timeZone)->format('d');

        if ($now < 16) {
            return Carbon::parse('first day of this month')->format('Y-m-d');;
        }

        return Carbon::parse('first day of this month')->addDays(15)->format('Y-m-d');
    }


}
