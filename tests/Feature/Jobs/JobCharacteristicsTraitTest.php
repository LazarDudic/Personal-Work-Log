<?php

namespace Tests\Feature\Jobs;

use App\Models\Job;
use App\Models\Overtime;
use App\Models\ShiftDifferential;
use App\Models\User;
use App\Models\Wage;
use Tests\TestCase;

class JobCharacteristicsTraitTest extends TestCase
{
    /** @test */
    public function trait_creates_database_record_for_all_job_related_tables()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->assertCount(0, Job::all());
        $this->assertCount(0, Wage::all());
        $this->assertCount(0, Overtime::all());
        $this->assertCount(0, ShiftDifferential::all());

        $this->post(route('jobs.store', [
            'title' => $this->faker->jobTitle
        ]));

        $this->assertCount(1, Job::all());
        $this->assertCount(1, Wage::all());
        $this->assertCount(1, Overtime::all());
        $this->assertCount(1, ShiftDifferential::all());
    }
}
