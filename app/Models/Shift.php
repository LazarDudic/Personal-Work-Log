<?php

namespace App\Models;

use App\Traits\CurrentPayPeriod;
use App\Traits\ShiftCalculation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory, ShiftCalculation, CurrentPayPeriod;

    protected $guarded = ['id'];
    public $timestamps = false;


    protected static function booted()
    {
        static::addGlobalScope('order-by-started-at', function (Builder $builder) {
            $builder->orderByDesc('started_at');
        });
    }
}
