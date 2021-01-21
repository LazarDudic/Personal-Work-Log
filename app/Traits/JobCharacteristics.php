<?php

namespace App\Traits;

use App\Models\Overtime;
use App\Models\ShiftDifferential;
use App\Models\Wage;

trait JobCharacteristics
{

    /**
     * Store all requested Characteristics to database
     *
     * @param $request
     * @param int $jobId
     */
    public function storeCharacteristics($request, int $jobId)
    {
        if ($request->hourly_rate) {
            $this->wage()->create($request->only(['hourly_rate', 'time_length', 'pay_period']));
        } else {
            Wage::create([
                'job_id' => $jobId
            ]);
        }


        if ($request->starting_hour) {
            $this->overtime()->create($request->only(['overtime_pay', 'calculated_by', 'starting_hour']));
        }  else {
            Overtime::create([
                'job_id' => $jobId
            ]);
        }

        if ($request->currency_amount || $request->percentage) {
            $data = $request->only(['start_at', 'finish_at', 'currency_amount', 'percentage']);

            if ($data['currency_amount'] && $data['percentage']) {
                $data['percentage'] = null;
            }

            if ($request->differential_days) {
                $data['differential_days'] = json_encode($request->differential_days);
            }

            $this->shiftDifferential()->create($data);
        }  else {
            ShiftDifferential::create([
                'job_id' => $jobId
            ]);
        }

        $this->tracking()->create(
            $request->only('wage', 'overtime', 'shift_differential', 'tips', 'bonuses', 'expenses')
        );

    }
}
