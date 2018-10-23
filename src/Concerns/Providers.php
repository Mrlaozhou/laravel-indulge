<?php

namespace Mrlaozhou\Indulge\Concerns;

trait Providers
{

    /**
     * @param bool $instant
     *
     * @return \Mrlaozhou\Indulge\Entities\Field|string
     */
    public function indulgeFieldProvider ($instant = true)
    {
        if( $instant ) {
            return app(config('indulge.providers.field'));
        }
        return config('indulge.providers.field');
    }

    /**
     * @param bool $instant
     *
     * @return \Mrlaozhou\Indulge\Entities\Option|string
     */
    public function indulgeOptionProvider ($instant = true)
    {
        if( $instant ) {
            return app(config('indulge.providers.option'));
        }
        return config('indulge.providers.option');
    }

    /**
     * @param bool $instant
     *
     * @return \Mrlaozhou\Indulge\Entities\Value|string
     */
    public function indulgeValueProvider ($instant = true)
    {
        if( $instant ) {
            return app(config('indulge.providers.value'));
        }
        return config('indulge.providers.value');
    }
}