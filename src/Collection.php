<?php

namespace Mrlaozhou\Indulge;

use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Arr;

class Collection extends BaseCollection
{
    /**
     * @param        $pid
     * @param string $selfKey
     * @param string $pidKey
     *
     * @return \Illuminate\Support\Collection
     */
    public function toTrees ($pid=0, $selfKey='id', $pidKey='pid')
    {
        $trees          =   [];
        foreach ( $this->items as $item ) {
            if( $item[$pidKey] == $pid ) {
                $item['children']     =   $this->toTrees( $item[$selfKey] );
                $this->forget( $item[$selfKey] );
                Arr::set( $trees, $item[$selfKey], $item );
            }
        }
        return new self($trees);
    }

    /**
     * @param int       $pid
     * @param self|null $lists
     * @param int       $level
     * @param string    $selfKey
     * @param string    $pidKey
     *
     * @return \Mrlaozhou\Indulge\Collection
     */
    public function toLists ($pid=0 ,self $lists=null, $level=1, $selfKey='id', $pidKey='pid')
    {
        $lists          =   $lists ?: new self([]);
        foreach ( $this->items as $key => $item ) {
            if( $item[$pidKey] == $pid ) {
                $item['level']        =   $level;
                $lists->push( $item );
                $this->forget($key);
                $this->toLists( $item[$selfKey], $lists, $level+1 );
            }
        }
        return $lists;
    }

    /**
     * @param int    $pid
     * @param string $selfKey
     * @param string $pidKey
     *
     * @return \Mrlaozhou\Indulge\Collection
     */
    public function children ($pid=0, $selfKey='id', $pidKey='pid')
    {
        return  $this->filter( function ($item) use ($pid, $selfKey, $pidKey){
            return $item[$pidKey]   ===  $pid;
        } );
    }
}