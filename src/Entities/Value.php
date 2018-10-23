<?php

namespace Mrlaozhou\Indulge\Entities;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table            =   'indulge_values';

    protected $dates            =   ['deleted_at'];

    protected $guarded          =   [
        'table', 'deleted_at'
    ];

    public $timestamps          =   false;
}