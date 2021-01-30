<?php

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWageRequest extends FormRequest
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
        return [
            'wage'                => ['nullable'],
            'hourly_rate'         => ['required', 'numeric', 'min:1',],
            'time_length'         => ['nullable', 'integer'],
            'pay_period'          => ['required', 'in:week,month,twiceEveryMonth', ],
            'pay_period_start_at' => ['nullable', 'date', 'required_if:pay_period,week,month'],
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
            'time_length.integer' => 'The time length of Pay Period must be an integer.',
        ];
    }
}
