<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Support\ServiceProvider;

class LaravelIndulgeServiceProvider extends ServiceProvider
{
    public function boot ()
    {
        if( $this->app->runningInConsole() ) {
            $this->registerMigrations();
        }
        $this->publishConfig();
        
        $this->mergeConfigFrom( __DIR__ . '/../config/indulge.php', 'indulge' );
    }

    public function register ()
    {
    }

    /**
     * publish config file
     */
    protected function publishConfig ()
    {
        $this->publishes( [
            __DIR__ . '/../config/indulge.php'  =>  config_path( 'indulge.php' )
        ] );
    }
    
    protected function registerMigrations ()
    {
        //  注册migrations文件
        $this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
    }
}