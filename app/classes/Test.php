<?php


namespace App\classes;

use App\Interfaces\TestInterface;

class Test implements TestInterface
{
    private Car $car;

    /**
     * Test constructor.
     * @param Car $car
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function testMe() : string {
        return "Lets test the Car: " . $this->car->wRoom();
    }

}
