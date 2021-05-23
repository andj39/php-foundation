<?php


namespace App\Exceptions;


use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ClassNotFoundException extends \Exception implements NotFoundExceptionInterface
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
