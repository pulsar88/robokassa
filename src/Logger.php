<?php

namespace Fillincode\Robokassa;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function log(...$args)
    {
        if (config('robokassa.log_during_testing')) {
            Log::channel(config('robokassa.log_driver'))->debug(...$args);
        }
    }
}