<?php


namespace App\classes\Router;

class RequestFactory
{

    public function createRequest() : Request {
        return new Request(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']);
    }

}
