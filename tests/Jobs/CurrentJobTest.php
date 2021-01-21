<?php

namespace Tests\Jobs;

use App\Models\Job;
use App\Models\User;
use Tests\TestCase;

class CurrentJobTest extends TestCase
{

    /** @test */
    public function first_job_is_automatically_current()
    {
        $user = User::factory()->create();
        $this->withoutMiddleware();

        $this->actingAs($user)->post(route('jobs.store'), [
            'title' => $this->faker->jobTitle
        ]);

        $job = $user->jobs()->latest('id')->first();

        $this->assertEquals(1, $job->current_job);

    }
    /** @test */
    public function every_job_after_fist_one_is_not_current()
    {
        $user = User::factory()->create();
        $this->withoutMiddleware();

        $this->actingAs($user)->post(route('jobs.store'), [
            'title' => $this->faker->jobTitle
        ]);
        $this->actingAs($user)->post(route('jobs.store'), [
            'title' => $this->faker->jobTitle
        ]);

        $job2 = $user->jobs()->latest('id')->first();

        $this->assertEquals(0, $job2->current_job);

    }

    /** @test */
    public function delete_current_job_passes_with_1_job_in_database()
    {
        $user = User::factory()->create();
        $this->withoutMiddleware();

        $job = $user->jobs()->create([
            'title' => $this->faker->jobTitle,
            'current_job' => 1
        ]);

        $response = $this->actingAs($user)->delete(route('jobs.destroy', $job->id));
        $response->assertSessionHas('success');
    }

    /** @test Can not delete current job if more then 1 job */
    public function delete_current_job_fails_with_more_then_1_job_in_database()
    {

        $user = User::factory()->create();
        $this->withoutMiddleware();

        $job1 = $user->jobs()->create([
            'title' => $this->faker->jobTitle,
            'current_job' => 1
        ]);
        $job2 = $user->jobs()->create([
            'title' => $this->faker->jobTitle,
            'current_job' => 0
        ]);

        $response = $this->actingAs($user)->delete(route('jobs.destroy', $job1->id));
        $response->assertSessionHasErrors([0], 'Please change your current job before delete.');
    }

    /** @test */
    public function method_UpdateCurrentJob_swap_current_job_values_passes()
    {
        $user = User::factory()->create();
        $this->withoutMiddleware();

        $job1 = $user->jobs()->create([
            'title' => $this->faker->jobTitle,
            'current_job' => 1
        ]);

        $job2 = $user->jobs()->create([
            'title' => $this->faker->jobTitle,
            'current_job' => 0
        ]);

        $this->actingAs($user)->put(route('jobs.current.update', $job2->id));
        $job1 = Job::find($job1->id);
        $job2 = Job::find($job2->id);

        $this->assertEquals(0, $job1->current_job);
        $this->assertEquals(1, $job2->current_job);
    }

    /** @test */
    public function current_job_id_is_accessible_only_for_auth_user()
    {
        $response = $this->get('/');

        $response->assertViewMissing('current_job_id');

        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/');

        $response->assertViewHas('current_job_id');
    }

}
