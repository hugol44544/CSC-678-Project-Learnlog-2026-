<?php

require __DIR__ . '/../src/classManagement.php';

use PHPUnit\Framework\TestCase;

class classValidationTests extends TestCase{

    //Classname tests
    function testClassCreationNameMin(){
        $classname = "Art"; //3 characters
        $this->assertTrue(classNameCheck($classname), "create class with name containing the minimum amount of required characters");
    }

    function testClassCreationNameMax(){
        $classname = "abcdefghijklmnop qrstuvwxyz1234567890-:."; //40 characters
        $this->assertTrue(classNameCheck($classname), "create class with name containing the maximum amount of required characters");
    }

    function testClassCreationNameMinPlusOne(){
        $classname = "ART1"; //4 characters
        $this->assertTrue(classNameCheck($classname), "create class with name containing one more than the minimum amount of required characters");
    }

    function testClassCreationNameMaxMinusOne(){
        $classname = "abcdefghijklmnop qrstuvwxyz1234567890-:"; //39 characters
        $this->assertTrue(classNameCheck($classname), "create class with name containing one less than the maximum amount of required characters");
    }

    function testClassCreationNameLess(){
        $classname = "PE"; //2 characters
        $this->assertFalse(classNameCheck($classname), "create class with name containing one less than the minimum amount of required characters");
    }

    function testClassCreationNameMore(){
        $classname = "abcdefghijklmnop qrstuvwxyz1234567890-:.!"; //41 characters
        $this->assertFalse(classNameCheck($classname), "create class with name containing one more than the maximum amount of required characters");
    }

    function testClassCreationNameEmpty(){
        $classname = ""; //0 characters
        $this->assertFalse(classNameCheck($classname), "create class with name containing no characters");
    }

    function testClassCreationNameWhitespace(){
        $classname = "          "; //Only whitespace
        $this->assertFalse(classNameCheck($classname), "create class with name containing only whitespace");
    }

}

?>