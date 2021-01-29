<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Tracking;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tracking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_id' => 1,
            'wage' => 1,
            'overtime' => 1,
            'shift_differential' => 1,
            'tips' => 1,
            'bonuses' => 1,
            'expenses' => 1,
        ];
    }
}
