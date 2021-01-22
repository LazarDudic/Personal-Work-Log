<?php

namespace Tests\Feature\Jobs;

use App\Models\Job;
use App\Models\Tracking;
use App\Models\User;
use Tests\TestCase;

class OvertimeTest extends TestCase
{
    /**
    /** @test */
    public function starting_hour_is_required()
    {
        $job = $this->createJob();
        $response = $this->patch(route('overtime.update', $job->id), [
            'starting_hour' => null
        ]);

        $response->assertSessionHasErrors(['starting_hour' => 'The starting hour field is required.']);
    }

    /** @test */
    public function overtime_pay_must_be_at_least_1()
    {
        $job = $this->createJob();
        $response = $this->patch(route('overtime.update', $job->id), [
            'overtime_pay' => 0
        ]);

        $response->assertSessionHasErrors(['overtime_pay' => 'The overtime pay must be at least 1.']);
    }

    /** @test */
    public function calculated_by_is_required()
    {
        $job = $this->createJob();
        $response = $this->patch(route('overtime.update', $job->id), [
            'calculated_by' => null
        ]);

        $response->assertSessionHasErrors(['calculated_by' => 'The calculated by field is required.']);
    }

    /** @test */
    public function calculated_by_must_contain_specific_value()
    {
        $job = $this->createJob();
        $response = $this->patch(route('overtime.update', $job->id), [
            'calculated_by' => 'random'
        ]);

        $response->assertSessionHasErrors(['calculated_by' => 'The selected calculated by is invalid.']);
    }

    /** @test */
    public function overtime_can_be_updated()
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('jobs.store'), [
            'title'=> $this->faker->jobTitle,
            'overtime'      => 1,
            'overtime_pay'  => 10,
            'starting_hour' => 40,
            'calculated_by' => 'week'
        ]);

        $job = Job::first();
        $this->assertEquals(1, $job->tracking->overtime);
        $this->assertEquals(10, $job->overtime->overtime_pay);
        $this->assertEquals(40, $job->overtime->starting_hour);
        $this->assertEquals('week', $job->overtime->calculated_by);

        $this->patch(route('overtime.update', $job->id), [
            'overtime_pay'  => 15,
            'starting_hour' => 8,
            'calculated_by' => 'shift'
        ]);

        $job = Job::first();
        $this->assertEquals(0, $job->tracking->overtime);
        $this->assertEquals(15, $job->overtime->overtime_pay);
        $this->assertEquals(8, $job->overtime->starting_hour);
        $this->assertEquals('shift', $job->overtime->calculated_by);
    }
    /** @test */
    public function tracking_overtime_mutator_converts_value_to_1()
    {
        $job = $this->createJob();
        $this->assertEquals(0, $job->tracking->overtime);

        $this->patch(route('overtime.update', $job->id), [
            'overtime' => 'on',
            'overtime_pay'  => 15,
            'starting_hour' => 40,
            'calculated_by' => 'week'
        ]);

        $job = Job::first();
        $this->assertEquals(1, $job->tracking->overtime);
    }

    private function createJob() : Job
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->post(route('jobs.store'), [
            'title' => $this->faker->jobTitle,
            'user_id' => $user->id
        ]);

        return Job::first();
    }

}
