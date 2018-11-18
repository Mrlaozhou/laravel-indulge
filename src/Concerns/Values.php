<?php

namespace Mrlaozhou\Indulge\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mrlaozhou\Indulge\Entities\Value;

trait Values
{

    /**
     * @param \Illuminate\Support\Collection $extensionAttributes
     *
     * @return bool|null|integer
     */
    public function createIndulgeValues (Collection $extensionAttributes)
    {
        if( ! $this->existsEntity() ) return false;

        if( $extensionAttributes->isEmpty() )   return null;

        return $this->indulgeValueProvider()->newQuery()
            ->insert( $this->fillExtensionValuesAttributes( $extensionAttributes )->toArray() );
    }

    /**
     * @param \Illuminate\Support\Collection $extensionAttributes
     *
     * @return bool|int|null
     */
    public function updateIndulgeValues (Collection $extensionAttributes)
    {
        //  删除原有值
        $this->deleteIndulgeValues( $extensionAttributes );
        //  重新添加
        return $this->createIndulgeValues( $extensionAttributes );
    }

    /**
     * @param \Illuminate\Support\Collection|null $extensionAttributes
     * @param \Illuminate\Support\Collection|null $extensionFields
     *
     * @return mixed
     */
    public function deleteIndulgeValues (Collection $extensionAttributes = null, Collection $extensionFields = null)
    {
        $valuesBuilder      =   $this->indulgeValueProvider()->newQuery()
            ->where( 'table', $this->getTable() )
            ->where( 'model_id', $this->getKey() );
        if( $extensionAttributes && $extensionAttributes->isNotEmpty() ) {
            //  扩展字段
            $extensionFields        =   $extensionFields ?: $this->fetchExtensionFields();
            $needDeleteFieldIds     =   $extensionFields->whereIn('name', $extensionAttributes->keys())
                                                          ->pluck('id')->toArray();
            return $valuesBuilder->whereIn('field_id',$needDeleteFieldIds)->delete();
        }

        return $valuesBuilder->delete();
    }

    public function indulgeValues ()
    {
        return $this->hasMany( $this->indulgeValueProvider(false), 'model_id' )->where('table', $this->getTable());
    }
}