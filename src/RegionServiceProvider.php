<?php

namespace Lysice\Region;

class RegionServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Region::class, function (){
            return new Region();
        });
        $this->app->alias(Region::class, 'region');
    }
}
