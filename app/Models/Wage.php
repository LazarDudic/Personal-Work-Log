<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

}
