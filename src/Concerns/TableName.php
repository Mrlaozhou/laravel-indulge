<?php

namespace Mrlaozhou\Indulge\Concerns;

trait TableName
{
    /**
     * 获取扩展字段表名
     *
     * @return string
     */
    public function getFieldTableName ()
    {
        return config( 'indulge.field.table' );
    }

    /**
     * 获取扩展字段值表名
     *
     * @return string
     */
    public function getValuesTableName ()
    {
        return config( 'indulge.value.table' );
    }
}