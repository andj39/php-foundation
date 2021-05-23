<?php


namespace App\classes;


use App\Interfaces\TestInterface;

class User
{
    private TestInterface $test;

    /**
     * User constructor.
     * @param TestInterface $test
     */
    public function __construct(TestInterface $test)
    {
        $this->test = $test;
    }

    public function test() : void {
        echo $this->test->testMe();
    }
}
