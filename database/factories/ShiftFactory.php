<?php

namespace Database\Factories;

use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shift::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_id' => 1,
            'started_at' => Carbon::now()->addHours(rand(0, 4))->addMinutes(rand(0, 59)),
            'finished_at' => Carbon::now()->addHours(rand(7, 19))->addMinutes(rand(0, 59)),
            'break_minutes' => rand(0, 100),
            'total_earnings' => mt_rand (30*10, 300*10) / 10,
            'total_working_minutes' => rand(300, 800),
            'regular_earnings' => mt_rand(10*10, 200*10) / 10,
            'overtime_earnings' => mt_rand(1*10, 100*10) / 10,
            'overtime_minutes' => rand(0, 300),
            'shift_differential_earnings' => mt_rand(1*10, 100*10) / 10,
            'shift_differential_minutes' => rand(0, 300),
            'tips' => rand(0, 60),
            'bonuses' => rand(0, 60),
            'expenses' => rand(0, 60),
        ];
    }
}
