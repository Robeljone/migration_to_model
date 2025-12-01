<?php

namespace RobelJone\MgrModel;

use Illuminate\Support\ServiceProvider;
use Autogen\Models\Commands\GenerateModelsCommand;

class AutogenModelServiceProvider extends ServiceProvider
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
