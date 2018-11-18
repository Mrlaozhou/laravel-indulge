<?php

namespace Mrlaozhou\Indulge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mrlaozhou\Indulge\Contracts\OptionInterface;

class Option extends Model implements OptionInterface
{
    use SoftDeletes;

    protected $table            =   'indulge_options';

    protected $dates            =   ['deleted_at'];

    protected $guarded          =   [
        'table', 'deleted_at'
    ];

    /**
     * @var array
     */
    private static $indulgeOriginCache  =   [];

    public function children ()
    {
        return $this->hasMany( self::class, 'pid', 'id' );
    }

    /**
     * @param int $pid
     *
     * @return \Illuminate\Support\Collection
     */
    public function trees($pid = 0)
    {
        //  原始数据
        return static::originCollection()->toTrees($pid);
    }

    /**
     * @param int $pid
     *
     * @return \Mrlaozhou\Extend\Collection
     */
    public function lists($pid = 0)
    {
        return static::originCollection()->toLists($pid);
    }

    /**
     * @return \Mrlaozhou\Extend\Collection
     */
    public function roots()
    {
        return static::originCollection()->children(0);
    }

    /**
     * @param int $pid
     *
     * @return \Mrlaozhou\Extend\Collection
     */
    public function child($pid = 0)
    {
        return static::originCollection()->children($pid);
    }

    /**
     * @return \Mrlaozhou\Extend\Collection
     */
    private static function originCollection ()
    {
        if( static::$indulgeOriginCache )
            return new \Mrlaozhou\Extend\Collection( static::$indulgeOriginCache );
        return new \Mrlaozhou\Extend\Collection(
            static::$indulgeOriginCache = (new static())->newQuery()->get()->toArray()
        );
    }
}