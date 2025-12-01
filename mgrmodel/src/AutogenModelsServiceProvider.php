<?php

namespace RobelJone\MgrModel;

use Illuminate\Support\ServiceProvider;
use RobelJone\MgrModel\Commands\GenerateModelsCommand;

class AutogenModelsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateModelsCommand::class,
            ]);
        }
    }

    public function register()
    {
        
    }
}
