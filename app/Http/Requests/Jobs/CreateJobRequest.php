<?php

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $differentialDays = ['nullable', 'array'];
        if ($this->shift_differential && !$this->start_at) {
            // start_at/finish_at or shift_differential is required if shift differential is checked
            array_push($differentialDays, 'required_if:shift_differential,on');
        }

        $percentage = ['nullable', 'integer', 'min:1'];
        if ($this->start_at || $this->differential_days && !$this->currency_amount) {
            // percentage or currency_amount is required if differential_days or start_at/finish_at present
            array_push($percentage, 'required_if:currency_amount,null');
        }

        return [
            'title'              => ['required', 'string', 'max:70'],
            'wage'               => ['nullable'],
            'hourly_rate'        => ['nullable', 'numeric', 'required_if:wage,1', 'min:1'],
            'time_length'        => ['nullable', 'integer'],
            'pay_period'         => ['nullable', 'in:week,month,day,twiceEveryMonth'],
            'overtime'           => ['nullable'],
            'overtime_pay'       => ['nullable', 'numeric', 'required_if:overtime,1', 'min:1'],
            'starting_hour'      => [
                'nullable',
                'integer',
                'required_with:overtime_pay,numeric',
                'required_if:calculated_by,week,payPeriod,shift',
                'required_if:overtime,1'
            ],
            'calculated_by'      => ['nullable', 'in:week,payPeriod,shift', 'required_with:starting_hour,integer'],
            'shift_differential' => ['nullable'],
            'start_at'           => ['nullable', 'date_format:H:i'],
            'finish_at'          => ['nullable', 'date_format:H:i', 'required_with:start_at,date_format:H:i'],
            'currency_amount'    => ['nullable', 'numeric', 'min:1'],
            'percentage'         => $percentage,
            'differential_days'  => $differentialDays,
            'tips'               => ['nullable'],
            'bonuses'            => ['nullable'],
            'expenses'           => ['nullable'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'hourly_rate.required_if' => 'The Hourly Rate field is required when Wage is on.',
            'starting_hour.required_with' => 'The Starting Hour field is required when Overtime Pay is present.',
            'overtime_pay.required_if' => 'The Overtime Pay field is required when Overtime is on.',
            'calculated_by.required_with' => 'The Calculated By field is required when Starting Hour is present.',
            'starting_hour.required_if' => 'The Starting Hour field is required.',
            'differential_days.required_if' => 'The Hours or Days are required when Shift Differential is on.',
            'finish_at.required_with' => 'The Ends At field is required when Starts At is present.',
            'percentage.required_if' => 'The Currency Amount or Percentage is required if you set Shift Differential Time or Day',
        ];
    }


}
