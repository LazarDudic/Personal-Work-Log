<?php

namespace App\Traits;

trait JobCharacteristics
{

    /**
     * Store all requested Characteristics to database
     *
     * @param $request
     */
    public function storeCharacteristics($request)
    {
        if ($request->hourly_rate) {
            $this->wage()->create($request->only(['hourly_rate', 'time_length', 'pay_period']));
        }

        if ($request->starting_hour) {
            $this->overtime()->create($request->only(['overtime_pay', 'calculated_by', 'starting_hour']));
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
        }

        $this->tracking()->create(
            $request->only('wage', 'overtime', 'shift_differential', 'tips', 'bonuses', 'expenses')
        );

    }
}
