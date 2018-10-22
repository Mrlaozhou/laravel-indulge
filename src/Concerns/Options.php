<?php

namespace Mrlaozhou\Indulge\Concerns;

use Illuminate\Support\Arr;
use Mrlaozhou\Indulge\Exceptions\IndulgeException;

trait Options
{
    public function createIndulgeFieldOption ($id, array $attributes)
    {
        //  当前字段
        $field          =   $this->fieldProvider()::query()->find($id);
        //  是否是选项字段
        if( in_array( $field->form_type, $this->getOptionFieldTypes() ) ) {
            throw new IndulgeException( sprintf('Field [%s] is not a option type.',$field->name) );
        }
        //
        return $this->optionProvider();
    }
}