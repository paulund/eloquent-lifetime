<?php

namespace Paulund\EloquentLifetime;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Paulund\EloquentLifetime\Console\Commands\ModelLifetime;

class EloquentLifetimeServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register() {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config' => config_path(),
        ], 'paulund/eloquent-lifetime');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ModelLifetime::class,
            ]);

            if (config('eloquent-lifetime.scheduled_command.enabled') === true) {
                $this->app->booted(function (): void {
                    $schedule = $this->app->make(Schedule::class);

                    $scheduleTime = config('eloquent-lifetime.scheduled_command.schedule');
                    if (method_exists($schedule->command(ModelLifetime::class), $scheduleTime)) {
                        $schedule->command(ModelLifetime::class)->$scheduleTime();
                    }
                });
            }
        }
    }
}
