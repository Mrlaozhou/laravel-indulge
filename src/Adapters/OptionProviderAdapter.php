<?php

namespace Mrlaozhou\Indulge\Adapters;

trait OptionProviderAdapter
{
    abstract static function fetchOptionsByParentId ($id);
    
    
    abstract static function fetchOptionsByParentKeywords ($keywods);
}