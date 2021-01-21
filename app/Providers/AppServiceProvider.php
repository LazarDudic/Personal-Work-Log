<?php

namespace App\Providers;

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
        view()->composer('*', function($view){
            if (\auth()->user()) {
                $currentUserId = optional(auth()->user()->jobs()->where('current_job', 1)->first())->id;
                $view->with('current_job_id', $currentUserId);
            }
        });

    }
}
