<?php

namespace App\Models;

use App\Traits\ShiftCalculation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory, ShiftCalculation;

    protected $guarded = ['id'];
    public $timestamps = false;

}
