<?php

namespace Tests\Jobs;

use App\Http\Requests\Jobs\CreateJobRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class JobFormRequestTest extends TestCase
{
    /** @test */
    public function hourly_rate_required_if_wage_on()
    {
        $rules = [
            'wage' => ['nullable'],
            'hourly_rate' => ['nullable', 'numeric', 'required_if:wage,1', 'min:1'],
        ];
        $attributes = [
            'wage' => 1,
            'hourly_rate' => null,
        ];

        $validator = $this->getValidator($attributes, new CreateJobRequest(), $rules);

        $this->assertEquals('The Hourly Rate field is required when Wage is on.',
            $validator->getMessageBag()->first('hourly_rate'));
    }


    /** @test */
    public function overtime_pay_required_if_overtime_on()
    {
        $rules = [
            'overtime' => ['nullable'],
            'overtime_pay' => ['nullable', 'numeric', 'required_if:overtime,1', 'min:1'],
        ];
        $attributes = [
            'overtime' => 1,
            'overtime_pay' => null,
        ];

        $validator = $this->getValidator($attributes, new CreateJobRequest(), $rules);

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function starting_hour_required_if_overtime_pay_present()
    {
        $rules = [
            'starting_hour' => ['required_with:overtime_pay,numeric'],
        ];
        $attributes = [
            'overtime_pay' => 1,
            'starting_hour' => null
        ];

        $validator = $this->getValidator($attributes, new CreateJobRequest(), $rules);

        $this->assertTrue($validator->fails());
    }

    /** @test */
    public function differential_days_or_start_at_required_if_shift_differential_on()
    {
        $start_at = [null, '12:00', '11:13', null];

        foreach ($start_at as $time) {
            $rules = ['differential_days' => ['nullable', 'array']];

            $attributes = [
                'shift_differential' => 1,
                'start_at' => $time,
                'differential_days' => null,
            ];

            if ($time === null) {
                array_push($rules['differential_days'],  'required_if:shift_differential,1');
            }

            $validator = $this->getValidator($attributes, new CreateJobRequest(), $rules);

            if ($time === null) {
                $this->assertEquals('The Hours or Days are required when Shift Differential is on.',
                    $validator->errors()->first('differential_days'));
            } else {
                $this->assertTrue($validator->passes()); // if time present validation passes
            }
        }
    }

    public function getValidator($attributes, CreateJobRequest $request, $rules = null)
    {
        if ($rules) {
            return Validator::make($attributes, $rules, $request->messages());
        }

        return Validator::make($attributes, $request->rules(), $request->messages());
    }
}
