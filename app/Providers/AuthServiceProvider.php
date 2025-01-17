<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Form;
use App\Policies\FormPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePolicies();
    }

    protected function configurePolicies()
    {
        $this->app->bind(Form::class, FormPolicy::class);
    }
} 