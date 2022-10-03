<?php


namespace Tests\Unit\Devvly\Subscription\Utils;


trait UnitTestHelper
{
    public function getMethod($class, $method){
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method;
    }
}