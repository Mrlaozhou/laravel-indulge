<?php

return [
    /**
     * 字段选项配置
     */
    'option'                    =>  [
        'table'             =>  'indulge_options',
        
        /**
         * 模型提供者
         */
        'provider'          =>  \Mrlaozhou\Indulge\Providers\Option::class
    ],

    /**
     * 扩展字段配置
     */
    'field'                     =>  [
        'table'             =>  'indulge_fields',
        'provider'          =>  \Mrlaozhou\Indulge\Providers\Field::class,
    ],

    /**
     * 扩展字段值配置
     */
    'value'                     =>  [
        'table'             =>  'indulge_values',
        'provider'          =>  \Mrlaozhou\Indulge\Providers\Value::class,
        
        'model_field'       =>  'model_id',
    ],

    'migrate'                   =>  [
        'path'              =>  __DIR__ . '/../database/migrations'
    ],
];