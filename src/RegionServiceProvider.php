<?php

namespace Lysice\Region;
use Lysice\Region\GenerateCommand;

class RegionServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    protected $commands = [
        GenerateCommand::class,
    ];

    public function register()
    {
        $this->app->singleton(Region::class, function (){
            return new Region();
        });
        $this->app->alias(Region::class, 'region');

        $this->registerCommand();
        $this->registerPublishing();
    }

    private function registerCommand()
    {
        $this->commands($this->commands);
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            // 配置文件config
            $this->publishes([__DIR__ . '/../config' => config_path()], 'region-config');
            // 数据库迁移
            $this->publishes([__DIR__ . '/../migrations' => database_path('migrations')], 'region-migrations');
        }
    }
}
