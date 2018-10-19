<?php

namespace Mrlaozhou\Indulge\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mrlaozhou\Indulge\Adapters\OptionProviderAdapter;


class Option extends Model
{
    use OptionProviderAdapter, SoftDeletes;
    
    protected $table            =   'indulge_options';
    
    protected $dates            =   ['deleted_at'];
    
    public static function fetchOptionsByParentId($id, $withSParent = false)
    {
        // TODO: Implement fetchOptionsByParentId() method.
        //  当前元素
        if( ! ( $parent = static::query()->find($id) ) ) {
            return false;
        }
        $children       =   static::query()
                                  ->where('pid', $parent->id)
                                  ->orderBy('weight','desc')
                                  ->get();

        return $withSParent ? $children->push($parent) : $children;
    }
    
    public static function fetchOptionsByParentKeywords($keywods, $withSParent = false)
    {
        // TODO: Implement fetchOptionsByParentKeywords() method.
    }
}