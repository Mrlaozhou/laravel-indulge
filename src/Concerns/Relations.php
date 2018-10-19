<?php

namespace Mrlaozhou\Indulge\Concerns;

trait Relations
{

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indulgeModelValueRelation ()
    {
        return $this->hasMany( $this->valueProvider(), 'model_id' )
            ->where( 'table', $this->getTable() );
    }

    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indulgeModelWithValueRelation ()
    {
        return $this->indulgeModelValueRelation();
    }
}