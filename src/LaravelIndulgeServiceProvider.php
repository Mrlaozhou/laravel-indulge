<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Support\ServiceProvider;
use Mrlaozhou\Indulge\Managers\OptionManager;

class LaravelIndulgeServiceProvider extends ServiceProvider
{
    public function boot ()
    {
        //  发布配置文件
        $this->publishConfig();
        //  终端下
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
        //  合并配置文件
        $this->mergeConfigFrom( __DIR__ . '/../config/indulge.php', 'indulge' );
        //  绑定对象
        $this->app->singleton( 'indulge.option', function () {
            return new OptionManager();
        } );
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