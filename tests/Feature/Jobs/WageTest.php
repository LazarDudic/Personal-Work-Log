<?php

namespace Tests\Feature\Jobs;

use App\Models\Job;
use App\Models\User;
use Tests\TestCase;

class WageTest extends TestCase
{
    /** @test */
    public function hourly_rate_is_required()
    {
        $job = $this->createJob();
        $response = $this->patch(route('wages.update', $job->id), [
            'hourly_rate' => ''
        ]);

        $response->assertSessionHasErrors(['hourly_rate' => 'The hourly rate field is required.']);
    }

    /** @test */
    public function hourly_rate_must_be_a_number()
    {
        $job = $this->createJob();
        $response = $this->patch(route('wages.update', $job->id), [
            'hourly_rate' => 'string'
        ]);

        $response->assertSessionHasErrors(['hourly_rate' => 'The hourly rate must be a number.']);
    }

    /** @test */
    public function time_length_must_be_an_integer()
    {
        $job = $this->createJob();
        $response = $this->patch(route('wages.update', $job->id), [
            'time_length' => 'string'
        ]);

        $response->assertSessionHasErrors(['time_length' => 'The time length of Pay Period must be an integer.']);
    }

    /** @test */
    public function pay_period_is_required()
    {
        $job = $this->createJob();
        $response = $this->patch(route('wages.update', $job->id), [
            'pay_period' => null
        ]);

        $response->assertSessionHasErrors(['pay_period' => 'The pay period field is required.']);
    }

    /** @test */
    public function pay_period_must_contain_specific_value()
    {

        $job = $this->createJob();
        $response = $this->patch(route('wages.update', $job->id), [
            'pay_period' => 'random'
        ]);

        $response->assertSessionHasErrors(['pay_period' => 'The selected pay period is invalid.']);
    }

    /** @test */
    public function wage_can_be_updated()
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('jobs.store'), [
            'title' => $this->faker->jobTitle,
            'wage' => 1,
            'hourly_rate' => 10,
            'time_length' => 5,
            'pay_period' => 'week'
        ]);

        $job = Job::first();
        $this->assertEquals(1, $job->tracking->wage);
        $this->assertEquals(10, $job->wage->hourly_rate);
        $this->assertEquals(5, $job->wage->time_length);
        $this->assertEquals('week', $job->wage->pay_period);

        $this->patch(route('wages.update', $job->id), [
            'hourly_rate' => 15,
            'time_length' => null,
            'pay_period' => 'month'
        ]);

        $job = Job::first();
        $this->assertEquals(0, $job->tracking->wage);
        $this->assertEquals(15, $job->wage->hourly_rate);
        $this->assertEquals(null, $job->wage->time_length);
        $this->assertEquals('month', $job->wage->pay_period);
    }
    /** @test */
    public function tracking_wage_mutator_converts_value_to_1()
    {
        $job = $this->createJob();
        $this->assertEquals(0, $job->tracking->wage);

        $this->patch(route('wages.update', $job->id), [
            'wage' => 'on',
            'hourly_rate' => 15,
            'time_length' => null,
            'pay_period' => 'month'
        ]);

        $job = Job::first();
        $this->assertEquals(1, $job->tracking->wage);
    }

}
