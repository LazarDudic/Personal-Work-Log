<?php

namespace App\Providers;

use App\Models\Job;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('current_job_id', Job::withoutGlobalScope('user')->where('current_job', 1)->first()->id);
    }
}
