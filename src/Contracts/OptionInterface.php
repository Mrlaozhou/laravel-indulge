<?php

namespace Mrlaozhou\Indulge\Contracts;

interface OptionInterface
{
    public function trees ($pid = 0);

    public function lists ($pid = 0);

    public function roots () ;

    public function child ($pid = 0);
}