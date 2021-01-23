<?php

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftDifferentialRequest extends FormRequest
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
            'shift_differential' => ['nullable'],
            'start_at'           => ['nullable', 'date_format:H:i', 'required_with:finish_at,date_format:H:i'],
            'finish_at'          => ['nullable', 'date_format:H:i', 'required_with:start_at,date_format:H:i'],
            'currency_amount'    => ['nullable', 'numeric', 'min:1'],
            'percentage'         => ['nullable', 'numeric', 'min:1', 'required_without:currency_amount'],
            'differential_days'  => ['required_if:start_at,null'],
        ];
    }

    public function messages()
    {
        return [
            'differential_days.required_if' => 'The Time or Differential Days is required.',
            'percentage.required_without' => 'The Currency Amount or Percentage is required.',
            'start_at.required_with' => 'The Start At field is required when Ends At is present.',
            'finish_at.required_with' => 'The Ends At field is required when Starts At is present.',
            'finish_at.date_format' => 'The Ends At does not match the format H:i.',
        ];
    }
}
