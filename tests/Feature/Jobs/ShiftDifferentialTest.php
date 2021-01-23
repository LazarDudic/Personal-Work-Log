<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;

class ShiftDifferentialTest extends TestCase
{
    /** @test */
    public function time_must_be_Hi_format()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'start_at' => time(),
            'finish_at' => time()
        ]);

        $response->assertSessionHasErrors(['start_at' => 'The start at does not match the format H:i.']);
        $response->assertSessionHasErrors(['finish_at' => 'The Ends At does not match the format H:i.']);

    }
    /** @test */
    public function start_at_or_finish_at_when_one_is_present()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'start_at' => null,
            'finish_at' => date('H:i')
        ]);
        $response->assertSessionHasErrors(['start_at']);
        $response->assertSessionDoesntHaveErrors(['finish_at']);

        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'start_at' => date('H:i'),
            'finish_at' => null
        ]);

        $response->assertSessionHasErrors(['finish_at']);
        $response->assertSessionDoesntHaveErrors(['start_at']);
    }

    /** @test */
    public function currency_amount_can_be_numeric_or_null()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'currency_amount' => null,
        ]);

        $response->assertSessionDoesntHaveErrors(['currency_amount']);

        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'currency_amount' => 1,
        ]);

        $response->assertSessionDoesntHaveErrors(['currency_amount']);
    }

    /** @test */
    public function currency_amount_must_be_at_least_1()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'currency_amount' => 0,
        ]);

        $response->assertSessionHasErrors(['currency_amount' => 'The currency amount must be at least 1.']);
    }

    public function percentage_can_be_null_or_numeric_and_min_1()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'percentage' => null,
        ]);

        $response->assertSessionDoesntHaveErrors(['currency_amount']);

        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'currency_amount' => 1,
        ]);

        $response->assertSessionDoesntHaveErrors(['currency_amount']);

        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'currency_amount' => 0,
        ]);

        $response->assertSessionHasErrors(['currency_amount' => 'The percentage must be at least 1.']);

    }

    /** @test */
    public function currency_amount_or_percentage_is_required()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'currency_amount' => null,
            'percentage' => null,
        ]);

        $response->assertSessionHasErrors(['percentage' => 'The Currency Amount or Percentage is required.']);
    }

    /** @test */
    public function only_currency_amount_or_percentage_can_be_present()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'start_at' => date('H:i'),
            'finish_at' => date('H:i'),
            'currency_amount' => 1,
            'percentage' => 1,
        ]);

        $response->assertSessionHasErrors([0], 'You can only chose one field between currency amount and percentage.');
    }

    /** @test */
    public function differential_days_is_required_if_time_is_not_present()
    {
        $job = $this->createJob();
        $response = $this->patch(route('shift-differentials.update', $job->id), [
            'start_at' => null,
            'finish_at' => null,
            'differential_days' => null
        ]);

        $response->assertSessionHasErrors(['differential_days' => 'The Time or Differential Days is required.']);

    }
}
