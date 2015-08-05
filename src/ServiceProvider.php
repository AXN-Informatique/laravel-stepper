<?php

namespace Axn\LaravelStepper;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['stepper'];
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('stepper', function($app) {
            return new Stepper($app);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/stepper.php', 'stepper'
        );

        $this->app->bind(
            'Axn\LaravelStepper\StepInterface',
            config('stepper.step.class')
        );
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/stepper.php' => config_path('stepper.php'),
        ]);
    }
}
