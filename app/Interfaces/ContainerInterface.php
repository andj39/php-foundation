<?php

namespace App\Interfaces;

use App\Exceptions\ClassNotFoundException;

interface ContainerInterface
{

    /**
     * @throws ClassNotFoundException
     */
    public function get($index);

}
