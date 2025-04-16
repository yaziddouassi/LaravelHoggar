<?php

namespace Hoggar\Hoggar;

use Illuminate\Support\ServiceProvider;


class HoggarServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/hoggar.php', 'hoggar'
        );
    }

   
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->commands([
            \Hoggar\Hoggar\Commands\HoggarCommand::class,
            \Hoggar\Hoggar\Commands\CreateUser::class,
        ]);
    }
}
