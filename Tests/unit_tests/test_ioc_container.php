<?php
 namespace Test\unit_tests;

use PHPUnit\Framework\TestCase;
use secureDB\container\container;

/**
 * writing a test case for the ioc container
 * 
 */
 class test_ioc_container extends TestCase
 {
        
        /**
         * test the has method
         * 
         */

         public function test_bind()
         {
               //mock the container
                $mock = $this->getMockBuilder(container::class)->getMock();
                $mock->expects($this->once())->method('bind')->with('message' , $this->callback(function ($value)
                {
                        return is_callable($value);
                }));
                //$mock->expects($this->once())->method('bind')->with('c:/project/secureDB/config');
                $mock->bind('message' , function($container){
                        echo "hello";
                });
                //$this->assertIsBool($mock->has())
         }
 }