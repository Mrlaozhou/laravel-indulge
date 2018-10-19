<?php

namespace Mrlaozhou\Indulge\Concerns;

trait Values
{
    //  获取扩展值
    protected function getIndulgeValues () 
    {
        return $this->indulgeModelValueRelation()->get();
    }
}