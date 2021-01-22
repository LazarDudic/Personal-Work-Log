<?php

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOvertimeRequest extends FormRequest
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
            'overtime'      => ['nullable'],
            'overtime_pay'  => ['nullable', 'numeric', 'min:1'],
            'starting_hour' => [ 'required', 'nullable', 'integer'],
            'calculated_by' => [ 'required', 'nullable', 'in:week,payPeriod,shift'],
        ];
    }
}
