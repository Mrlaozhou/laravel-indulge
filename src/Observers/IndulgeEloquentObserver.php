<?php

namespace Mrlaozhou\Indulge\Observers;

use Illuminate\Database\Eloquent\Model;
use Mrlaozhou\Indulge\Concerns\Fields;

class IndulgeEloquentObserver
{
    public function retrieved() {}

    /**
     * @param \Illuminate\Database\Eloquent\Model|\Mrlaozhou\Indulge\Indulge $model
     *
     * @return bool
     */
    public function creating(Model $model)
    {
        //  分离属性
        list( $extensionAttributes, $originAttributes )     =   $model->splitMixedAttributes( $model->getAttributes() );
        //  模型属性重置
        $model->setRawAttributes( $originAttributes->toArray() );
        $model->setShortRent( 'createExtensionAttributes', $extensionAttributes );
        return true;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|\Mrlaozhou\Indulge\Indulge $model
     *
     * @return bool
     */
    public function created(Model $model)
    {
        //  获取扩展数据
        $extensionAttributes            =   $model->getShortRent('createExtensionAttributes');
        //  存储扩展字段
        $model->indulgeValuesCreate( $extensionAttributes );
        //
        $model->setRawAttributes( $extensionAttributes->merge( $model->toArray() )->toArray() );
    }

    public function updating() {}

    public function updated() {}

    public function saving() {}

    public function saved() {}

    public function restoring() {}

    public function restored() {}

    public function deleting() {}

    public function deleted() {}

    public function forceDeleted() {}

}