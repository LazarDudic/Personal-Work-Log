<?php

namespace App\Http\Livewire\Jobs;

use Livewire\Component;

class FormInput extends Component
{
    public $requestedInputs = [];
    public $count = 1;
    public $wage = false;
    public $overtime = false;
    public $shiftDifferential = false;
    public $extraTracking = false;

    public function mount()
    {
        if (count(old())) {
            $oldInputs = array_keys(old());
            $this->showRequestedInputsAfterErrorOccurred($oldInputs);
        }
    }
    public function addOrRemoveWage()
    {
        $found = $this->removeRequestedInput('wage');


        if (! $found) {
            $this->wage = $this->count++;
            $checked = old('wage') === null ? '' : 'checked';

            $week  = old('pay_period') == 'week' ? 'selected' : '';
            $month = old('pay_period') == 'month' ? 'selected' : '';
            $twiceEveryMonth = old('pay_period') == 'twiceEveryMonth' ? 'selected' : '';

            $this->requestedInputs[$this->wage] = '
                <hr><h4 class="text-info">Wage</h4>
                <div class="form-group d-lg-flex justify-content-between">
                    <div class="custom-control custom-checkbox align-self-center">
                        <input type="checkbox" name="wage" class="custom-control-input"
                            id="wage" value="1" ' . $checked . '>
                        <label class="custom-control-label" for="wage">
                            <span class="badge">Keep track of wage</span>
                        </label>
                    </div>
                    <div class="d-lg-flex justify-content-center">
                    <span class="badge align-self-center">Hourly Rate</span>
                        <input type="number" name="hourly_rate" class="form-control w-50" min="0" step="any"
                        value="' . old('hourly_rate') . '">
                    </div>
                </div>
                <div class="form-group d-lg-flex align-items-center">
                    <span class="badge">Pay Period</span>
                    <input type="number" name="time_length" class="form-control w-50 mr-2"
                        min="1" value="' . old('time_length') . '">
                    <select name="pay_period" class="custom-select custom-select">
                        <option value="week" '.$week.'>Week(s)</option>
                        <option value="month" '.$month.'>Month(s)</option>
                        <option value="twiceEveryMonth" '.$twiceEveryMonth.'>1-15, 16-end</option>
                    </select>
                </div>
                ';
        }
    }

    public function addOrRemoveOvertime()
    {
        $found = $this->removeRequestedInput('overtime');

        if (! $found) {
            $this->overtime = $this->count++;

            $checked = old('overtime') === null ? '' : 'checked';

            $week      = old('calculated_by') == 'week' ? 'selected' : '';
            $payPeriod = old('calculated_by') == 'payPeriod' ? 'selected' : '';
            $shift     = old('calculated_by') == 'shift' ? 'selected' : '';

            $this->requestedInputs[$this->overtime] = '
                  <hr><h4 class="text-info">Overtime</h4>
                    <div class="form-group d-lg-flex justify-content-between">
                    <div class="custom-control custom-checkbox align-self-center">
                        <input type="checkbox" name="overtime" class="custom-control-input"
                        id="overtime" '.$checked.' value="1">
                        <label class="custom-control-label" for="overtime">
                            <span class="badge">Keep track of overtime</span>
                        </label>
                    </div>
                    <div class="d-lg-flex justify-content-center">
                    <span class="badge align-self-center">Overtime Pay</span>
                        <input type="number" name="overtime_pay" class="form-control w-50"
                            min="0" step="any" value="' . old('overtime_pay') . '">
                    </div>
                </div>
                <div class="form-group d-lg-flex align-items-center">
                    <span class="badge">Starting After</span>
                    <input type="number" name="starting_hour" class="form-control w-50 mr-2"
                    min="0" placeholder="Hours" value="' . old('starting_hour') . '">
                    <select name="calculated_by" class="custom-select custom-select">
                        <option value="" selected disabled>Calculated By</option>
                        <option '.$week.' value="week">Week</option>
                        <option '.$payPeriod.' value="payPeriod">Pay Period</option>
                        <option '.$shift.' value="shift">Shift</option>
                    </select>
                </div>
                ';
        }
    }

    public function addOrRemoveShiftDifferential()
    {
        $found = $this->removeRequestedInput('shiftDifferential');

        if (! $found) {
            $this->shiftDifferential = $this->count++;
            $checked   = old('shift_differential') === null ? '' : 'checked';

            $oldDifferentialDays = old('differential_days') ?? [];
            $monday    = in_array(1 , $oldDifferentialDays) ? 'checked' : '';
            $tuesday   = in_array(2 , $oldDifferentialDays) ? 'checked' : '';
            $wednesday = in_array(3 , $oldDifferentialDays) ? 'checked' : '';
            $thursday  = in_array(4 , $oldDifferentialDays) ? 'checked' : '';
            $friday    = in_array(5 , $oldDifferentialDays) ? 'checked' : '';
            $saturday  = in_array(6 , $oldDifferentialDays) ? 'checked' : '';
            $sunday    = in_array(7 , $oldDifferentialDays) ? 'checked' : '';

            $this->requestedInputs[$this->shiftDifferential] = '
                <hr><h4 class="text-info">Shift Differential</h4>
                <div class="form-group d-lg-flex justify-content-between">
                    <div class="custom-control custom-checkbox align-self-center">
                        <input type="checkbox" name="shift_differential" class="custom-control-input"
                               id="shiftDifferential" value="1" '.$checked.' >
                        <label class="custom-control-label" for="shiftDifferential">
                            <span class="badge">Keep track of shift differential</span>
                        </label>
                    </div>
                </div>
                <div class="form-group d-flex">
                    <div class="mr-2">
                        <span class="badge">Starts At</span>
                        <input  type="time" name="start_at" class="form-control" value="' . old('start_at') . '">
                    </div>
                    <div>
                        <span class="badge">Ends At</span>
                        <input type="time" name="finish_at" class="form-control" value="' . old('finish_at') . '">
                    </div>
                </div>
                <div class="form-group d-flex">
                    <span class="badge align-self-end pb-3">Increase By</span>
                    <div class="mr-xl-2">
                        <span class="badge">Currency Amount</span>
                        <input name="currency_amount" class="form-control" type="number" step="any"
                            value="' . old('currency_amount') . '">
                    </div>
                    <div>
                        <span class="badge">Percentage</span>
                        <input name="percentage" class="form-control" type="number"
                            value="' . old('percentage') . '">
                    </div>
                </div>
                <div class="form-group">
                    <h5><span class="badge">Whole Days Differential</span></h5>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]"  value="1"
                            class="custom-control-input" id="monday" '.$monday.'>
                        <label class="custom-control-label" for="monday">
                            <span class="badge">Monday</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]" value="2"
                            class="custom-control-input" id="tuesday" '.$tuesday.' >
                        <label class="custom-control-label" for="tuesday">
                            <span class="badge">Tuesday</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]" value="3"
                            class="custom-control-input" id="wednesday" '.$wednesday.'>
                        <label class="custom-control-label" for="wednesday">
                            <span class="badge">Wednesday</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]" value="4"
                            class="custom-control-input" id="thursday" '.$thursday.'>
                        <label class="custom-control-label" for="thursday">
                            <span class="badge">Thursday</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]" value="5"
                            class="custom-control-input" id="friday" '.$friday.'>
                        <label class="custom-control-label" for="friday">
                            <span class="badge">Friday</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]" value="6"
                            class="custom-control-input" id="saturday" '.$saturday.'>
                        <label class="custom-control-label" for="saturday">
                            <span class="badge">Saturday</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="differential_days[]" value="7"
                            class="custom-control-input" id="sunday" '.$sunday.'>
                        <label class="custom-control-label" for="sunday">
                            <span class="badge">Sunday</span>
                        </label>
                    </div>
                </div>';
        }
    }

    public function addOrRemoveExtraTracking()
    {
        $found = $this->removeRequestedInput('extraTracking');

        if (! $found) {
            $this->extraTracking = $this->count++;
            $tips     = old('tips') === null ? '' : 'checked';
            $bonuses  = old('bonuses') === null ? '' : 'checked';
            $expenses = old('expenses') === null ? '' : 'checked';


            $this->requestedInputs[$this->extraTracking] = '
                <hr><h4 class="text-info">Extra Tracking</h4>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="tips" class="custom-control-input"
                             id="tips" value="1" '.$tips.'>
                        <label class="custom-control-label" for="tips">
                            <span class="badge">Keep track of tips</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="bonuses" class="custom-control-input"
                            id="bonuses" value="1" '.$bonuses.'>
                        <label class="custom-control-label" for="bonuses">
                            <span class="badge">Keep track of bonuses</span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="expenses" class="custom-control-input"
                            id="expenses" value="1" '.$expenses.'>
                        <label class="custom-control-label" for="expenses">
                            <span class="badge">Keep Track of expenses</span>
                        </label>
                    </div>
            ';
        }
    }

    public function removeRequestedInput(string $inputExists) : bool
    {
        if ($this->$inputExists === false) {
            return false;
        }

        unset($this->requestedInputs[$this->$inputExists]);
        $this->$inputExists = false;
        return true;
    }

    public function showRequestedInputsAfterErrorOccurred(array $oldInputs) : void
    {
        if (array_intersect(['wage', 'hourly_rate'], $oldInputs)) {
            $this->addOrRemoveWage();
        }

        if (array_intersect(['overtime', 'overtime_pay', 'starting_hour'], $oldInputs)) {
            $this->addOrRemoveOvertime();
        }

        $shiftDifferential = [
            'shift_differential',
            'start_at',
            'differential_days'
        ];

        if (array_intersect($shiftDifferential, $oldInputs)) {
            $this->addOrRemoveShiftDifferential();
        }

        if (array_intersect(['tips', 'bonuses', 'expenses'], $oldInputs)) {
            $this->addOrRemoveExtraTracking();
        }
    }


    public function render()
    {
        return view('livewire.jobs.file-input');
    }


}
