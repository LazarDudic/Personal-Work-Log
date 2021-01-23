<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDifferential extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function getStartAtAttribute($startAt)
    {
        return $startAt ? date('H:i', strtotime($startAt))
                        : null;
    }

    public function getFinishAtAttribute($finishAt)
    {
        return $finishAt ? date('H:i', strtotime($finishAt))
                         : null;
    }

}

