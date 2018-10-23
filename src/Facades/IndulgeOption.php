<?php

namespace Mrlaozhou\Indulge\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class IndulgeOption
 *
 * @method static lists ($pid = 0)
 * @method static trees ($pid = 0)
 * @method static roots ()
 * @method static child ($pid = 0)
 *
 * @package Mrlaozhou\Indulge\Facades
 */
class IndulgeOption extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'indulge.option';
    }
}