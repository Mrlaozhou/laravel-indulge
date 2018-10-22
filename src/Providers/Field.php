<?php

namespace Mrlaozhou\Indulge\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use SoftDeletes;
    
    protected $table            =   'indulge_fields';

    protected $guarded          =   [
        'deleted_at'
    ];
    
    protected $dates            =   ['deleted_at'];

    public function options ()
    {
        if( ! $this->option_id ) {
            return null;
        }
        return $this->hasMany( Option::class, 'id', 'option_id' )->get();
    }
}