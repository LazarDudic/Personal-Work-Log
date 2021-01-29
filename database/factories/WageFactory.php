<?php

namespace Database\Factories;

use App\Models\Wage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_id' => 1,
            'hourly_rate' => 10,
            'time_length' => 2,
            'pay_period' => 'week',
            'pay_period_start_at' => Carbon::now()->subDay(),
        ];
    }
}
