<?php

namespace Mrlaozhou\Indulge\Concerns;

use function foo\func;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

trait Fields
{
    public function fetchMixedFields ()
    {

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fetchExtensionFields ()
    {
        return $this->indulgeFieldProvider()->newQuery()
            ->where('table', $this->getTable())->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fetchExtensionFieldsWithOptions ()
    {
        return $this->indulgeFieldProvider()->newQuery()->with('options')
                    ->where('table', $this->getTable())->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fetchInnateFields ()
    {
        return collect( Schema::getColumnListing( $this->getTable() ) );
    }

    /**
     * @param array                               $mixedAttributes
     * @param \Illuminate\Support\Collection|null $extensionFields
     *
     * @return \Illuminate\Support\Collection
     */
    public function splitMixedFieldAttributes (array $mixedAttributes, Collection $extensionFields = null)
    {
        //  扩展字段
        $extensionFields        =   $extensionFields ?: $this->fetchExtensionFields();
        //  扩展字段name集
        $extensionFieldsLists   =   $extensionFields->pluck('name');

        return collect( $mixedAttributes )->partition( function ($item, $key) use ($extensionFieldsLists){
            return $extensionFieldsLists->contains($key);
        } );
    }

    /**
     * @param \Illuminate\Support\Collection      $extensionValuesAttributes
     * @param \Illuminate\Support\Collection|null $extensionFields
     *
     * @return \Illuminate\Support\Collection
     */
    public function fillExtensionValuesAttributes (Collection $extensionValuesAttributes, Collection $extensionFields = null)
    {
        //  扩展字段
        $extensionFields        =   $extensionFields ?: $this->fetchExtensionFields();
        $extensionFieldsByName  =   $extensionFields->keyBy('name');

        return $extensionValuesAttributes->map( function ($item, $key) use ($extensionFieldsByName){
            $fieldEntity        =   $extensionFieldsByName->get($key);
            return [
                'table'     =>  $this->getTable(),
                'model_id'  =>  $this->getKey(),
                'field_id'  =>  $fieldEntity->getKey(),
                'value'     =>  is_null( $item ) ? $fieldEntity->default : $item,
            ];
        } );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $extensionValues
     * @param \Illuminate\Support\Collection|null      $extensionFields
     *
     * @return \Illuminate\Support\Collection
     */
    public function extensionValuesToKV (\Illuminate\Database\Eloquent\Collection $extensionValues, Collection $extensionFields = null)
    {
        //  扩展字段
        $extensionFields            =   $extensionFields ?: $this->fetchExtensionFields();
        $extensionFieldsKeyByName   =   $extensionFields->keyBy('name');
        //  扩展值
        $extensionValuesByFieldId   =   $extensionValues->keyBy('field_id');

        return $extensionFieldsKeyByName->map( function ($item) use ($extensionValuesByFieldId) {
            //  当前字段值
            $valueEntity        =   $extensionValuesByFieldId->get($item->id);
            return $valueEntity ? $valueEntity->value : $item->default;
        } );

    }

    /**
     * @param array $attributes
     *
     * @return \Mrlaozhou\Indulge\Entities\Field|null
     */
    public function createIndulgeField (array $attributes)
    {
        $attributes['table']        =   $this->getTable();
        return $this->indulgeFieldProvider()->newQuery()->create( $attributes );
    }

    /**
     * @param       $id
     * @param array $attributes
     *
     * @return bool|null
     */
    public function updateIndulgeField ($id, array $attributes)
    {
        //  field entity
        $fieldEntity            =   $this->findIndulgeField( $id );

        if( ! $fieldEntity )    return null;
        //
        Arr::forget($attributes, 'table');

        return $fieldEntity->update( $attributes );
    }

    /**
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteIndulgeField ($id)
    {
        //  field entity
        $fieldEntity            =   $this->findIndulgeField($id);

        if( ! $fieldEntity )  return null;

        return $fieldEntity->delete();
    }

    /**
     * @param $id
     *
     * @return bool|null
     */
    public function destroyIndulgeField ($id)
    {
        //  field entity
        $fieldEntity            =   $this->findIndulgeField($id);

        if( ! $fieldEntity )  return null;

        return $fieldEntity->forceDelete();
    }

    /**
     * @param $id
     *
     * @return \Mrlaozhou\Indulge\Entities\Field|null
     */
    public function findIndulgeField ($id)
    {
        return $this->indulgeFieldProvider()
                    ->newQuery()->where('table', $this->getTable())
                                ->where('id',$id)->first();
    }
}