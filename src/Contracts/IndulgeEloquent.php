<?php

namespace Mrlaozhou\Indulge\Contracts;

interface IndulgeEloquent
{

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function indulge_find();

//    public function indulge_get();
//
//    public function indulge_update();
//
//    public function indulge_save();
//
//    public function indulge_destroy();
}