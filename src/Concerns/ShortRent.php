<?php

namespace Mrlaozhou\Indulge\Concerns;

use Illuminate\Support\Arr;

trait ShortRent
{

    /**
     * @var array
     */
    private static $indulgeShortRent;

    /**
     * @param $target string
     * @param $attributes
     *
     * @return array
     */
    public function setShortRent ($target, $attributes)
    {
        return Arr::set( static::$indulgeShortRent, $target, $attributes );
    }

    /**
     * @param $target
     *
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getShortRent ($target)
    {
        if( Arr::exists( static::$indulgeShortRent, $target ) )
            return Arr::pull( static::$indulgeShortRent, $target );
        return collect([]);
    }
}