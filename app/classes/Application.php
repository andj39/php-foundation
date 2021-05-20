<?php


namespace App\classes;


use App\Interfaces\ContainerInterface;

class Application
{

    private ContainerInterface $container;

    /**
     * Application constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run() {
        $user = $this->container->get('App\classes\User');
        $user->test();

    }

}
