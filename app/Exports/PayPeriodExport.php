<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class PayPeriodExport implements FromCollection, WithHeadings
{
    private $total;

    public function __construct($total)
    {
        $this->total = $total;
    }

    public function collection()
    {
        return new Collection([
            [
                $this->total['total_earnings'],
                $this->total['regular_earnings'],
                $this->total['overtime_earnings'],
                $this->total['shift_differential_earnings'],
                $this->total['tips'],
                $this->total['bonuses'],
                $this->total['expenses'],
                $this->total['total_working_minutes'],
                $this->total['overtime_minutes'],
                $this->total['shift_differential_minutes'],
                $this->total['break_minutes'],
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            [
                'Total',
                'Regular',
                'Overtime',
                'Shift Differential',
                'Tips',
                'Bonuses',
                'Expenses',

                'Total Hours',
                'Overtime Hours',
                'Shift Differential Hours',
                'Break Hours',
            ],
        ];
    }
}
