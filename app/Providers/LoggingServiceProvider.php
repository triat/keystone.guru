<?php

namespace App\Providers;

use App\Service\WowTools\Logging\WowToolsServiceLogging;
use App\Service\WowTools\Logging\WowToolsServiceLoggingInterface;
use Illuminate\Support\ServiceProvider;

class LoggingServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->bind(WowToolsServiceLoggingInterface::class, WowToolsServiceLogging::class);
    }

}
