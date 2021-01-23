<?php

namespace Tests;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker, RefreshDatabase;

    public function createJob() : Job
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
