<?php

namespace Tests\Feature\Jobs;

use App\Models\Job;
use Tests\TestCase;

class TrackingUpdateTest extends TestCase
{
    /** @test */
    public function tracking_can_have_only_1_or_0_as_a_value()
    {
        $job = $this->createJob();

        $response = $this->patch(route('tracking.update',$job->id), [
            'tips' => 1,
            'bonuses' => 1,
            'expenses' => 1,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $response = $this->patch(route('tracking.update',$job->id), [
            'tips' => 0,
            'bonuses' => 0,
            'expenses' => 0,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $response = $this->patch(route('tracking.update',$job->id), [
            'tips' => 'on',
            'bonuses' => 'on',
            'expenses' => 'on',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function tracking_can_be_updated()
    {
        $job = $this->createJob();
        $this->assertEquals(0, $job->tracking->tips);
        $this->assertEquals(0, $job->tracking->bonuses);
        $this->assertEquals(0, $job->tracking->expenses);

        $this->patch(route('tracking.update',$job->id), [
            'tips' => 1,
            'bonuses' => 1,
            'expenses' => 1,
        ]);

        $job = Job::first();
        $this->assertEquals(1, $job->tracking->tips);
        $this->assertEquals(1, $job->tracking->bonuses);
        $this->assertEquals(1, $job->tracking->expenses);
    }
}
