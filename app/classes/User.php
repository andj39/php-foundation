<?php


namespace App\classes;


class User
{
    private Test $test;

    /**
     * User constructor.
     * @param Test $test
     */
    public function __construct(Test $test)
    {
        $this->test = $test;
    }

    public function test() : void {
        echo $this->test->testMe();
    }
}
