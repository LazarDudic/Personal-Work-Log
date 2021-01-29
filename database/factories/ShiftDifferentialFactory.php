<?php

namespace Database\Factories;

use App\Models\ShiftDifferential;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftDifferentialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShiftDifferential::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_id' => 1,
            'start_at' => '22:00:00',
            'finish_at' => '07:00:00',
            'differential_days' => null,
            'percentage' => null,
            'currency_amount' => 3,
        ];
    }
}
