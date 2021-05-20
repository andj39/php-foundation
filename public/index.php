<?php

use App\classes\Test;
use App\classes\User;

require __DIR__ .'/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$container = new \App\classes\Container();
$container->register(User::class , 'App\classes\User' );
$container->register(\App\Interfaces\TestInterface::class,'\App\classes\Test');

$application = new \App\classes\Application($container);

$application->run();

