<?php

namespace LaravelChaos\Testing;

use Illuminate\Console\Scheduling\Schedule;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use LaravelChaos\Testing\Commands\ChaosCommand;

class ChaosServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-chaos-testing')
            ->hasConfigFile('chaos')
            ->hasCommand(ChaosCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->publishes([
            __DIR__.'/../config/chaos.php' => config_path('chaos.php'),
        ], 'laravel-chaos-testing-config');
    }

    public function packageBooted(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('chaos:process')->hourly();
        });
    }
}
