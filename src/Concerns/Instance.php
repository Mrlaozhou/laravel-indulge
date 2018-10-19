<?php

namespace Mrlaozhou\Indulge\Concerns;

trait Instance
{
    /**
     * 获取扩展字段选项模型
     *
     * @return \Mrlaozhou\Indulge\Providers\Option|null
     */
    public function optionProvider ()
    {
        return config( 'indulge.option.provider' );
    }

    /**
     * 获取扩展模型
     *
     * @return \Mrlaozhou\Indulge\Providers\Field|null;
     */
    public function fieldProvider ()
    {
        return config( 'indulge.field.provider' );
    }

    /**
     * 获取扩展值模型
     *
     * @return \Mrlaozhou\Indulge\Providers\Value|null
     */
    public function valueProvider ()
    {
        return config( 'indulge.value.provider' );
    }
}