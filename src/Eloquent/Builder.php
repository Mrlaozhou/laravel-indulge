<?php

namespace Mrlaozhou\Indulge\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Mrlaozhou\Indulge\Indulge;

class Builder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * @param array $columns
     * @param bool  $indulge
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function get ($columns = ['*'], $indulge=true)
    {
        if( method_exists( $this->model, 'isIndulged' ) && $this->model->isIndulged() && $indulge ) {
            //  当前模型的扩展字段列表
            $extensionFields            =   $this->model->fetchExtensionFields();
            $mixedWheres                =   $this->getQuery()->wheres;
            list( $extensionWheres, $innateWheres ) =   $this->model->splitMixedFieldAttributes(
                collect($mixedWheres)->keyBy('column')->toArray(),
                $extensionFields
            );
            //  重置查询条件
            $this->getQuery()->wheres   =   $innateWheres->toArray();
            //  with
            $this->with( 'indulgeValues' );
            //  未处理的数据
            $untreatedCollection        =   parent::get($columns);

            //  遍历处理
            return  $untreatedCollection->map(function(Model $model) use($extensionFields){
                return $model->setRawAttributes(
                    $this->model->extensionValuesToKV( $model->indulgeValues, $extensionFields )
                                ->merge( $model->getAttributes() )->toArray()
                );
            });
        }

        return parent::get($columns);
    }
}