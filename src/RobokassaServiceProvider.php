<?php

namespace Fillincode\Robokassa;

use Illuminate\Support\ServiceProvider;

class RobokassaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/robokassa.php' => config_path('robokassa.php'),
        ]);
    }
}