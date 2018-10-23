<?php

namespace Mrlaozhou\Indulge\Managers;

use Mrlaozhou\Indulge\Contracts\OptionInterface;
use Mrlaozhou\Indulge\Exceptions\Exception;

class OptionManager extends Manager implements OptionInterface
{

    /**
     * @var OptionInterface
     */
    protected $option;

    /**
     * OptionManager constructor.
     *
     * @throws \Mrlaozhou\Indulge\Exceptions\Exception
     */
    public function __construct()
    {
        $this->option       =   $this->indulgeOptionProvider();
        if( ! $this->option instanceof OptionInterface ) {
            throw new Exception( sprintf( "Option provider [%s] should implements \Mrlaozhou\Indulge\Contracts\OptionInterface ", $this->indulgeOptionProvider( false ) ) );
        }
    }

    public function trees($pid = 0)
    {
        return $this->option->trees($pid);
    }

    public function lists($pid = 0)
    {
        return $this->option->lists($pid);
    }

    public function roots()
    {
        return $this->option->roots();
    }

    public function child($pid = 0)
    {
        return $this->option->child($pid);
    }
}