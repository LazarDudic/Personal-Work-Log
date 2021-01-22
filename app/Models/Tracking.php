<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;
    protected $table = 'tracking';

    protected $guarded = ['id'];
    public $timestamps = false;


    public function setWageAttribute($wage)
    {
        $this->attributes['wage'] = $wage ? 1 : 0;
    }

    public function setOvertimeAttribute($overtime)
    {
        $this->attributes['overtime'] = $overtime ? 1 : 0;
    }

    public function setShiftDifferentialAttribute($shiftDifferential)
    {
        $this->attributes['shift_differential'] = $shiftDifferential ? 1 : 0;
    }

    public function setTipsAttribute($tips)
    {
        $this->attributes['tips'] = $tips ? 1 : 0;
    }

    public function setBonusesAttribute($bonuses)
    {
        $this->attributes['bonuses'] = $bonuses ? 1 : 0;
    }    public function setExpensesAttribute($expenses)
    {
        $this->attributes['expenses'] = $expenses ? 1 : 0;
    }

}
