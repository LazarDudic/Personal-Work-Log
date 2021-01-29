<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(1)->create();
         \App\Models\Job::factory(1)->create();
         \App\Models\Shift::factory(100)->create();
         \App\Models\Tracking::factory(1)->create();
         \App\Models\Wage::factory(1)->create();
         \App\Models\Overtime::factory(1)->create();
         \App\Models\ShiftDifferential::factory(1)->create();

    }
}
