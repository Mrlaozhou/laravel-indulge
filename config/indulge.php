<?php

return [
    'providers'                 =>  [
        /**
         *
         * Indulge option provider
         */
        'option'            =>  \Mrlaozhou\Indulge\Entities\Option::class,

        /**
         *
         * Indulge field provider
         */
        'field'             =>  \Mrlaozhou\Indulge\Entities\Field::class,

        /**
         *
         * Indulge value provider
         */
        'value'             =>  \Mrlaozhou\Indulge\Entities\Value::class
    ],
];