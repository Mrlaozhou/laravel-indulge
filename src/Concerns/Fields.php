<?php

namespace Mrlaozhou\Indulge\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Mrlaozhou\Indulge\Exceptions\IndulgeException;
use Mrlaozhou\Indulge\Requests\FieldsCreateRequest;
use Mrlaozhou\Indulge\Requests\FieldsUpdateRequest;

trait Fields
{
    /**
     * 需要options的字段类型
     *
     * @var array
     */
    protected $indulgeOptionFields      =   [
        'select', 'checkbox', 'radio'
    ];

    /**
     * @var Collection | array
     */
    private $indulgeFields;

    /**
     * 获取所有字段
     *
     * @return \Illuminate\Support\Collection
     */
    public function getIndulgeFields ($type = null)
    {
        return is_null($type) ? $this->initIndulgeFields() : $this->initIndulgeFields()->get( $type );
    }

    /**
     * 获取所有字段精简模式
     *
     * @return \Illuminate\Support\Collection
     */
    public function getIndulgeSlimFields ()
    {
        return $this->getOriginFields()->merge(
            $this->getExtensionFields()->pluck('name')
        );
    }

    /**
     * 获取原本字段
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOriginFields ()
    {
        return $this->getIndulgeFields('origin');
    }

    /**
     * 获取扩展字段
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getExtensionFields ()
    {
        return $this->getIndulgeFields('extension');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function initIndulgeFields ()
    {
        if( $this->indulgeFields )  return $this->indulgeFields;

        return $this->indulgeFields  =   collect( [
            'origin'        =>  collect( Schema::getColumnListing( $this->getTable() ) ),
            'extension'     =>  $this->fieldProvider()::query()->where('table', $this->getTable())
                                     ->select()->orderBy('weight', 'desc')->get(),
        ] );
    }

    /**
     * 获取扩展字段with选项
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getExtensionFieldsWithOption ()
    {
        $extensionFields        =   $this->getExtensionFields();

        $extensionFields->flatMap( function ($item){
            $item->options      =   [];
            if(
                in_array( $item->form_type, $this->getOptionFieldTypes() )
                && ($item->option_id)
            ) {
                $item->options  =   $this->optionProvider()::fetchOptionsByParentId( $item->option_id )->toArray();
            }
            return $item;
        } );

        return $extensionFields;
    }

    /**
     * @param $extName
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getExtensionFieldsByExtName ($extName)
    {
        if( is_string( $extName ) ) {
            $field = $this->getExtensionFields()->firstWhere( 'name' , '=', $extName );
            return $field ?: [];
        }

        if( is_array( $extName ) ) {
            return $this->getExtensionFields()->whereIn('name', $extName)->keyBy('name');
        }

        if( $extName instanceof Collection ) {
            return $this->getExtensionFields()->whereIn('name', $extName->toArray())->keyBy('name');
        }
        return [];
    }

    /**
     * 拆分混合字段
     * @param array $attributes
     *
     * @return \Illuminate\Support\Collection (扩展字段在前)
     */
    public function splitMixedAttributes (array $attributes)
    {
        //  获取所有字段
        $mixedFields            =   $this->getIndulgeFields();
        //  原始字段
        $originFields           =   $mixedFields->get( 'origin' );
        //  扩展字段
        $extensionFields        =   $mixedFields->get( 'extension' );

        return collect( $attributes )->partition( function ($item, $key)
            use ($originFields, $extensionFields){
            return $extensionFields->pluck('name')->contains($key);
        } );
    }

    /**
     * 扩展值填充
     *
     * @param \Illuminate\Support\Collection      $attributesCollection
     *
     * @return \Illuminate\Support\Collection | array
     */
    public function fillExtensionAttributes (Collection $attributesCollection)
    {
        //  获取字段信息
        $extensionFields            =   $this->getExtensionFieldsByExtName(
            $attributesCollection->keys()
        );
        //  new attributes
        $filledAttributes           =   collect([]);
        //  填充
        $attributesCollection->flatMap( function ($item, $key) use ($extensionFields , $filledAttributes) {
            $filledAttributes->push( [
                'table'         =>  $this->getTable(),
                'model_id'      =>  $this->getKey() ?? 0,   //  关联模型主键
                'field_id'      =>  $extensionFields->get($key)->id,
                'value'         =>  is_null( $item ) ? $extensionFields->get($key)->ext_default : $item
            ] );
        } );
        return $filledAttributes;
    }

    /**
     * 手动填充扩展值
     */
    public function manualFillExtensionAttributes (Collection $extensionFields, Collection $attributesCollection)
    {
        //  new attributes
        $filledAttributes           =   collect([]);
        //  填充
        $attributesCollection->flatMap( function ($item, $key) use ($extensionFields , $filledAttributes) {
            $filledAttributes->push( [
                'table'         =>  $this->getTable(),
                'model_id'      =>  $this->getKey() ?? 0,   //  关联模型主键
                'field_id'      =>  $extensionFields->get($key)->id,
                'value'         =>  is_null( $item ) ? $extensionFields->get($key)->ext_default : $item
            ] );
        } );
        return $filledAttributes;
    }

    /**
     * 扩展字段键值对处理
     * @param \Illuminate\Support\Collection $extensionsCollection
     *
     * @return \Illuminate\Support\Collection
     */
    public function valuesDataToKeyValue (Collection $extensionsCollection)
    {
        if( $extensionsCollection->isEmpty() )return collect([]);
        //  获取扩展字段
        $extensionFields            =   $this->getExtensionFields()->pluck('name', 'id');
        //  携带字段名
        $extensionsCollection->flatMap(function ($item) use($extensionFields){
            $item->field_name       =   $extensionFields->get( $item->field_id );
            return $item;
        } );
        //  键值对
        return $extensionsCollection->pluck( 'value', 'field_name' );
    }

    /**
     * 获取需要options的字段类型
     *
     * @return array
     */
    public function getOptionFieldTypes ()
    {
        return $this->indulgeOptionFields;
    }

    /**
     * @param array      $attributes
     * @param array|null $rules
     *
     * @return mixed
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function createIndulgeField (array $attributes,array $rules = null)
    {
        //  添加table字段
        $attributes['table']        =   $this->getTable();
//        //  构造验证
//        $rules                      =   $rules ?:  (new FieldsCreateRequest())->rules();
//        $validator                  =   Validator::make( $attributes, $rules );
//
//        if( $validator->fails() ) {
//            throw new IndulgeException( $validator->errors() );
//        }

        return $this->fieldProvider()::create($attributes);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteIndulgeField ($id)
    {
        return $this->fieldProvider()::query()->where('table', $this->getTable())
            ->where('id', $id)->delete();
    }


    /**
     * @param            $id
     * @param array      $attributes
     * @param array|null $rules
     *
     * @return mixed
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function updateIndulgeField ($id, array $attributes, array $rules=null)
    {
        $fieldPrimaryKey            =   $id ?: $attributes['id'];
//        //  构造验证
//        $rules                      =   $rules ?:  (new FieldsUpdateRequest())->rules();
//        $validator                  =   Validator::make( $attributes, $rules );
//
//        if( $validator->fails() ) {
//            throw new IndulgeException( $validator->errors() );
//        }
        return $this->fieldProvider()::query()->where('table', $this->getTable())
                                              ->where('id', $fieldPrimaryKey)
                                              ->update($attributes);
    }
}