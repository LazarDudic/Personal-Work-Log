<?php

namespace App\Services;

use App\Models\Wage;
use Carbon\Carbon;

class PayPeriodHistory
{
    private Wage $wage;

    public function __construct(Wage $wage)
    {
        $this->wage = $wage;
    }

    public function getDates() : array
    {
        if ($this->wage->pay_period == 'week') {
            return $this->weekPayPeriodHistory();
        }

        if ($this->wage->pay_period == 'month') {
            return $this->monthPayPeriodHistory();
        }

        if ($this->wage->pay_period == 'twiceEveryMonth') {
            return $this->twiceEveryMonthPayPeriodHistory();
        }
    }

    private function weekPayPeriodHistory() : array
    {
        $length = $this->wage->timelength ?? 1;
        $weeks = Carbon::now(\auth()->user()->timezone)->diffInWeeks($this->wage->pay_period_start_at);
        $payPeriodStart = Carbon::parse($this->wage->pay_period_start_at);

        $payPeriodDates = [];
        $len = $length;
        while ($weeks  > $len) {
            $payPeriodDates[] = [
                    'started_at' => $payPeriodStart->format('Y-m-d'),
                    'finished_at' => $payPeriodStart->addWeeks($length)->format('Y-m-d')
                ];
            $len += $length;
        }

        return $payPeriodDates;
    }

    private function monthPayPeriodHistory() : array
    {
        $length = $this->wage->timelength ?? 1;
        $months = Carbon::now(\auth()->user()->timezone)->diffInMonths($this->wage->pay_period_start_at);
        $payPeriodStart = Carbon::parse($this->wage->pay_period_start_at);

        $payPeriodDates = [];
        $len = $length;
        while ($months  > $len) {
            $payPeriodDates[] = [
                'started_at' => $payPeriodStart->format('Y-m-d'),
                'finished_at' => $payPeriodStart->addMonths($length)->format('Y-m-d')
            ];
            $len += $length;
        }

        return $payPeriodDates;
    }

    public function twiceEveryMonthPayPeriodHistory() : array
    {
        $jobStarted = $this->wage->job->created_at;

        $monthStarted = $this->getMonthStarted($jobStarted);

        $diffInMonths = $monthStarted->diffInMonths(Carbon::now(\auth()->user()->timezone));
        $countPayPeriods = $diffInMonths * 2;
        $c = 0;
        $payPeriodDates = [];

        while ($countPayPeriods > $c) {
            if ($c % 2 == 0 || $c == 0) {
                $monthStarted = $this->getMonthStarted($jobStarted);

                $payPeriodDates[] = [
                    'started_at' => $monthStarted->addMonths($c / 2)->format('Y-m-d'),
                    'finished_at' => $monthStarted->addDays(14)->format('Y-m-d')
                ];
            } else {
                $payPeriodDates[] = [
                    'started_at' => $monthStarted->addDay()->format('Y-m-d'),
                    'finished_at' => $monthStarted->addMonth()
                        ->firstOfMonth()
                        ->subDay()
                        ->format('Y-m-d')
                ];
            }

            $c++;
        }

        return $payPeriodDates;

    }

    /**
     * @param $jobStarted
     * @return Carbon
     */
    private function getMonthStarted($jobStarted): Carbon
    {
        $monthStarted = Carbon::createFromDate(
            $jobStarted->format('Y'),
            $jobStarted->format('m'),
            1,

        );
        return $monthStarted;
    }


}
