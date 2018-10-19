<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Trait Indulge
 *
 * @package Mrlaozhou\Indulge
 */
trait Indulge
{
    use Concerns\Instance, 
        Concerns\Fields,
        Concerns\TableName,
        Concerns\Relations;

    /**
     * 创建
     * @param array $attributes
     *
     * @return array
     */
    public function indulgeCreate(array $attributes = [])
    {
        //  事务操作
        DB::transaction( function () use ($attributes){
            //  分离数据
            list( $extensionAttributes, $originAttributes )     =   $this->splitMixedAttributes( $attributes );
            //  存储原始表信息
            $newModel                   =   static::create( $originAttributes->toArray() );
            //  存储扩展值
            return $this->indulgeValuesCreate( $extensionAttributes, $newModel );
        } );
        return $attributes;
    }

    /**
     * 软删除
     *
     * @return mixed
     */
    public function indulgeDelete ()
    {
        return $this->delete();
    }

    /**
     * 更新数据
     *
     * @param array $attributes
     *
     * @return array|bool
     */
    public function indulgeUpdate (array $attributes = []) 
    {
        if( ! $this->exists )   return false;
        //  结果集
        $result             =   [];
        //  事务操作
        DB::transaction( function () use ($attributes, & $result) {
            //  分离数据
            list( $extensionAttributes, $originAttributes )     =   $this->splitMixedAttributes( $attributes );
            //  更新原始数据
            $result['master']       =   $this->update( $originAttributes->toArray() );
            //  更新扩展数据
            $result['extension']    =   $this->indulgeValuesUpdate($extensionAttributes, $this);
        } );
        return $result;
    }

    /**
     * 永久删除
     *
     * @return bool
     */
    public function indulgeDestroy ()
    {
        DB::beginTransaction();
        try {
            //  是否软删除
            if( $this->trashed() ) {
                $this->forceDelete();
            } else {
                $this->delete();
            }
            //  删除扩展值
            $this->indulgeValuesDestroy();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param null  $id
     * @param array $columns
     *
     * @return bool|\Illuminate\Support\Collection
     */
    public function indulgeFind ($id = null, $columns = ['*'])
    {
        if( $this->exists ) {
            //  数据模型
            return $this->valuesDataToKeyValue( $this->indulgeValuesFind() )
                        ->merge( $this->toArray() );
        } else if ( $id ) {
            //  实例模型
            if( $thisModel = $this->newQuery()->find($id, $columns) ) {
                return $thisModel->indulgeFind();
            }
            return false;
        }
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indulgeList () 
    {
        return $this->newQuery()->with('indulgeModelWithValueRelation');
    }

    /**
     * 存储扩展值
     *
     * @param \Illuminate\Support\Collection $extensionAttributes
     * @param \Illuminate\Database\Eloquent\Model|self $newModel
     *
     * @return bool
     */
    protected function indulgeValuesCreate (Collection $extensionAttributes,self $newModel)
    {
        //  数据填充
        $filledExtensionAttributes      =   $this->fillExtensionAttributes( $extensionAttributes, $newModel );
        //  入库
        return $this->valueProvider()::query()->insert(
            $filledExtensionAttributes->toArray()
        );
    }

    /**
     * @return mixed
     */
    protected function indulgeValuesDelete () 
    {   
        return ;
    }

    /**
     * 永久删除扩展值
     */
    protected function indulgeValuesDestroy ()
    {
        return $this->valueProvider()::query()
                                     ->where('table', $this->getTable())
            ->where($this->getKeyName(), $this->getKey())
            ->delete();
    }

    /**
     * 更新扩展数据
     * @param \Illuminate\Support\Collection $extensionAttributes
     * @param self                           $model
     *
     * @return bool
     */
    protected function indulgeValuesUpdate (Collection $extensionAttributes,self $model)
    {
        // TODO BUG  未传数据也被删除
        //  删除原数据
        $model->indulgeValuesDestroy();
        //  重新存储
        return $this->indulgeValuesCreate($extensionAttributes, $model);
    }

    //  获取扩展值

    /**
     * 注(命名歧义)：虽然获取多条数据 ，但是每次是批量获取 所以后缀为 " find "
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function indulgeValuesFind ()
    {
        return $this->indulgeModelValueRelation()->get();
    }
}