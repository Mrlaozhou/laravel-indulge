<?php

namespace Mrlaozhou\Indulge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use SoftDeletes;

    protected $table            =   'indulge_fields';

    protected $dates            =   ['deleted_at'];

    protected $guarded          =   [
        'table', 'deleted_at'
    ];

    public function options ()
    {
        return $this->hasMany( Option::class, 'pid', 'option_id' );
    }
}