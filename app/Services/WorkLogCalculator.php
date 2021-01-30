<?php

namespace App\Services;

class WorkLogCalculator
{
    private $shifts;
    private $data = [
        'break_minutes' => 0,
        'total_earnings' => 0,
        'total_working_minutes' => 0,
        'regular_earnings' => 0,
        'overtime_earnings' => 0,
        'overtime_minutes' => 0,
        'shift_differential_earnings' => 0,
        'shift_differential_minutes' => 0,
        'tips' => 0,
        'bonuses' => 0,
        'expenses' => 0
    ];

    public function __construct($shifts)
    {
        $this->shifts = $shifts;
    }

    public function calculateHoursAndPay()
    {
        foreach ($this->shifts as $shift) {
            $this->data['break_minutes'] += $shift->break_minutes;
            $this->data['total_earnings'] += $shift->total_earnings;
            $this->data['total_working_minutes'] += $shift->total_working_minutes;
            $this->data['regular_earnings'] += $shift->regular_earnings;
            $this->data['overtime_earnings'] += $shift->overtime_earnings;
            $this->data['overtime_minutes'] += $shift->overtime_minutes;
            $this->data['shift_differential_earnings'] += $shift->shift_differential_earnings;
            $this->data['shift_differential_minutes'] += $shift->shift_differential_minutes;
            $this->data['tips'] += $shift->tips;
            $this->data['bonuses'] += $shift->bonuses;
            $this->data['expenses'] += $shift->expenses;
        }

        $this->data['break_minutes'] = convert_minutes_to_hours($this->data['break_minutes']);
        $this->data['total_working_minutes'] = convert_minutes_to_hours($this->data['total_working_minutes']);
        $this->data['overtime_minutes'] = convert_minutes_to_hours($this->data['overtime_minutes']);
        $this->data['shift_differential_minutes'] = convert_minutes_to_hours($this->data['shift_differential_minutes']);

        return $this->data;
    }


}
