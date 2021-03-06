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
        'deleted_at'
    ];

    public function options ()
    {
        return $this->hasMany( config('indulge.providers.option'), 'pid', 'option_id' )->where('pid', '!=', 0);
    }
}