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
     */
    public function created(Model $model)
    {
        if( ($extensionAttributes = $model->getShortRent('createExtensionAttributes'))->isNotEmpty() ) {
            //  存储扩展字段
            $model->indulgeValuesCreate( $extensionAttributes );
            //
            $model->setRawAttributes( $extensionAttributes->merge( $model->toArray() )->toArray() );
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|\Mrlaozhou\Indulge\Indulge $model
     *
     * @return bool
     */
    public function updating(Model $model)
    {
        //  分离属性
        list( $extensionAttributes, $originAttributes )     =   $model->splitMixedAttributes( $model->getAttributes() );
        //  模型属性重置
        $model->setRawAttributes( $originAttributes->toArray() );
        $model->setShortRent( 'updateExtensionAttributes', $extensionAttributes );
        return true;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|\Mrlaozhou\Indulge\Indulge $model
     */
    public function updated(Model $model)
    {
        if( ($extensionAttributes = $model->getShortRent('updateExtensionAttributes'))->isNotEmpty() ) {
            $model->indulgeValuesUpdate( $extensionAttributes, $model );
            $model->setRawAttributes( $model->indulgeValuesFinds()->toArray() );
        }
    }

    public function saving() {}

    public function saved() {}

    public function restoring() {}

    public function restored() {}

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function deleting(Model $model) {}

    public function deleted() {}

    /**
     * @param \Illuminate\Database\Eloquent\Model|\Mrlaozhou\Indulge\Indulge $model
     */
    public function forceDeleted(Model $model)
    {
        $model->indulgeModelValueRelation()->delete();
    }

}