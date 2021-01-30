<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Job extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at'];

    /**
     * The "booted" method of the model.
     * User can only see his jobs
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', auth()->user()->id);
        });
    }

    public function wage()
    {
        return $this->hasOne(Wage::class);
    }

    public function overtime()
    {
        return $this->hasOne(Overtime::class);
    }

    public function shiftDifferential()
    {
        return $this->hasOne(ShiftDifferential::class);
    }

    public function tracking()
    {
        return $this->hasOne(Tracking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
