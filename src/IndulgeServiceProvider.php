<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Support\ServiceProvider;

class IndulgeServiceProvider extends ServiceProvider
{
    public function boot ()
    {
        $this->publishConfig();
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
            __DIR__ . 'config/indulge.php'  =>  config_path( 'indulge.php' )
        ] );
    }
}