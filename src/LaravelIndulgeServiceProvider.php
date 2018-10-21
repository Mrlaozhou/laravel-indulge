<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Support\ServiceProvider;

class LaravelIndulgeServiceProvider extends ServiceProvider
{
    public function boot ()
    {
        $this->publishConfig();
        if( $this->app->runningInConsole() ) {
            //  注册命令
            $this->commands( [
                Commands\MigrateCommand::class,
                Commands\RollbackCommand::class
            ] );
        }
    }

    public function register ()
    {
        $this->mergeConfigFrom( __DIR__ . '/../config/indulge.php', 'indulge' );
    }

    /**
     * publish config file
     */
    protected function publishConfig ()
    {
        $this->publishes( [
            __DIR__ . '/../config/indulge.php'  =>  config_path( 'indulge.php' )
        ], 'config' );
    }
    
    protected function registerMigrations ()
    {
        //  注册migrations文件
        $this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
    }
}