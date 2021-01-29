<?php

namespace Database\Factories;

use App\Models\Overtime;
use Illuminate\Database\Eloquent\Factories\Factory;

class OvertimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Overtime::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_id' => 1,
            'overtime_pay' => 3,
            'calculated_by' => 'shift',
            'starting_hour' => 8,
        ];
    }
}
