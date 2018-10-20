<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mrlaozhou\Indulge\Exceptions\IndulgeException;
use Mrlaozhou\Indulge\Observers\IndulgeEloquentObserver;


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

    protected $indulgeShortRent        =   [

    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::observe( IndulgeEloquentObserver::class );
    }

    public function setShortRent($target,$attributes)
    {
        return $this->indulgeShortRent[$target]     =   $attributes;
    }

    /**
     * @param $target
     *
     * @return null|Collection
     */
    public function getShortRent($target)
    {
        return $this->indulgeShortRent[$target] ?? null;
    }

    /**
     * Create
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function indulgeCreate(array $attributes = [])
    {
        //  开启事务
        DB::beginTransaction();
        try {
            //  分离数据
            list( $extensionAttributes, $originAttributes )     =   $this->splitMixedAttributes( $attributes );
            //  创建原始数据
            $created            =   $this->indulgeOriginCreate( $originAttributes );
            //  创建扩展数据
            $created->indulgeValuesCreate( $extensionAttributes );
            //  事务提交
            DB::commit();
            return $created->getNewSelfInstance( $extensionAttributes->toArray() );
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new IndulgeException( $exception->getMessage() );
        }
    }

    /**
     * 创建原始数据
     *
     * @param \Illuminate\Support\Collection $originAttributes
     *
     * @return Model|$this
     */
    private function indulgeOriginCreate (Collection $originAttributes)
    {
        return $this->newQueryWithoutScopes()->create( $originAttributes->toArray() );
    }

    /**
     * 创建扩展数据
     *
     * @param \Illuminate\Support\Collection $extensionAttributes
     *
     * @return bool|null
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function indulgeValuesCreate (Collection $extensionAttributes)
    {
        //  判断是否是数据模型
        $this->isExistsModel();
        //  扩展数据为空
        if( $extensionAttributes->isEmpty() ) {
            return null;
        }
        //  数据填充
        $filledExtensionAttributes      =   $this->fillExtensionAttributes( $extensionAttributes );
        //  入库
        return $this->valueProvider()::query()->insert(
            $filledExtensionAttributes->toArray()
        );
    }

    /**
     * Delete
     * @return mixed|bool
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function indulgeDelete ()
    {
        $this->isExistsModel();

        return $this->delete();
    }

    /**
     * @return bool
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function indulgeDestroy ()
    {
        $this->isExistsModel();
        DB::beginTransaction();
        try {
            //  删除扩展数据
            $this->indulgeValuesDestroy();
            //  删除原始数据
            $this->forceDelete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new IndulgeException( $exception->getMessage() );
        }
    }

    /**
     * @param array|null $fields
     *
     * @return int
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    protected function indulgeValuesDestroy (array $fields = null)
    {
        //  模型是否合法
        $this->isExistsModel();
        //  query
        $valuesQueryBuilder         =   $this->valueProvider()::query()
                                             ->where('table', $this->getTable())
                                             ->where('model_id', $this->getKey());
        if( $fields ) {
            //  获取要删除的字段ID
            $fields_id              =   $this->getExtensionFields()->whereIn('name', $fields)
                                             ->pluck('id')->toArray();
            return  $valuesQueryBuilder->whereIn('field_id', $fields_id)->delete();
        }
        return  $valuesQueryBuilder->delete();
    }

    /**
     * @param array $attributes
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function indulgeUpdate (array $attributes = []) 
    {
        //  判断是否是数据模型
        $this->isExistsModel();
        DB::beginTransaction();
        try {
            //  分离数据
            list( $extensionAttributes, $originAttributes )     =   $this->splitMixedAttributes( $attributes );
            //  更新原始数据
            $this->indulgeOriginUpdate( $originAttributes );
            //  更新扩展数据
            $this->indulgeValuesUpdate($extensionAttributes, $this);
            DB::commit();
            return $this->indulgeFind();
        }catch ( \Exception $exception) {
            DB::rollBack();
            throw new IndulgeException( $exception->getMessage() );
        }
    }

    /**
     * @param \Illuminate\Support\Collection $originAttributes
     *
     * @return $this
     */
    private function indulgeOriginUpdate (Collection $originAttributes)
    {
        return $this->update( $originAttributes->toArray() );
    }

    /**
     * @param \Illuminate\Support\Collection      $extensionAttributes
     * @param \Illuminate\Database\Eloquent\Model|self $model
     *
     * @return bool|null
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    protected function indulgeValuesUpdate (Collection $extensionAttributes, Model $model)
    {
        //  删除原数据
        $model->indulgeValuesDestroy($extensionAttributes->keys()->toArray());
        //  重新存储
        return $model->indulgeValuesCreate($extensionAttributes);
    }

    /**
     * @param null  $id
     * @param array $columns
     *
     * @return bool|Model
     */
    public function indulgeFind ($id = null, $columns = ['*'])
    {
        if( $this->exists ) {
            //  数据模型
            return $this->getNewSelfInstance(
                $this->valuesDataToKeyValue( $this->indulgeValuesFind() )->toArray()
                );
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
     * 获取扩展值
     * 注(命名歧义)：虽然获取多条数据 ，但是每次是批量获取 所以后缀为 " find "
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function indulgeValuesFind ()
    {
        return $this->indulgeModelValueRelation()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indulgeList () 
    {
        return $this->newQuery()->with('indulgeModelWithValueRelation');
    }

    /**
     * @return bool
     * @throws \Mrlaozhou\Indulge\Exceptions\IndulgeException
     */
    public function isExistsModel ()
    {
        if( ! $this->exists ) {
            throw new IndulgeException( 'Model is not exists.' );
        }
        return true;
    }

    /**
     *
     * @param array      $extensionAttributes
     * @param array|null $originAttributes
     *
     * @return Model
     */
    private function getNewSelfInstance( array $extensionAttributes, array $originAttributes = null )
    {
        return new static( array_merge(
            is_null( $originAttributes ) ? $this->toArray() : $originAttributes,
            $extensionAttributes
            ) );
    }
}