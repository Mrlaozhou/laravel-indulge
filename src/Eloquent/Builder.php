<?php

namespace Mrlaozhou\Indulge\Eloquent;

use Illuminate\Database\Eloquent\Model;

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
            $fixedWheres                =   $this->getQuery()->wheres;
            list( $extensionWheres, $originWheres ) =   $this->model->splitMixedAttributes(
                collect($fixedWheres)->keyBy('column')->toArray()
            );
            //  重置查询条件
            $this->getQuery()->wheres   =   $originWheres->toArray();
            //  with
            $this->with( 'indulgeModelWithValueRelation' );
            //  未处理的数据
            $untreatedCollection        =   parent::get($columns);
            //  当前模型的扩展字段列表
            $extensionFields            =   $this->model->getExtensionFields();
            //  遍历处理
            return  $untreatedCollection->map(function(Model $model) use($extensionFields){
                return $model->setRawAttributes(
                    $this->model->valuesDataToKeyValue( $model->indulgeModelWithValueRelation )
                        ->merge( $model->getAttributes() )->toArray()
                );
            });
        }

        return parent::get($columns);
    }
}