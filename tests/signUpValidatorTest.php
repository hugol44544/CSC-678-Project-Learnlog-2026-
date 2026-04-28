<?php

require __DIR__ . '/../src/signUpValidation.php';

use PHPUnit\Framework\TestCase;

class signUpValidatorTest extends TestCase{

    private $connection;

    private function connectToDatabase() {
        if ($this->connection == null) {
            require 'config/config.php';
            $this->connection = $conn;
        }
        return $this->connection;
    }

    //USERNAME TESTS
    public function testUsernameMin(){
        $instance = new signUpValidator();
        $username = "user1"; // 5 characters
        $this->assertEquals(true, $instance->usernameCheck($username), "create username with minimum required characters");
    }

    public function testUsernameMax(){
        $instance = new signUpValidator();
        $username = "testusername12345678"; // 20 characters
        $this->assertEquals(true, $instance->usernameCheck($username), "create username with maximum required characters");
    }

    public function testUsernameMinMinusOne(){
        $instance = new signUpValidator();
        $username = "user01"; // 6 characters
        $this->assertEquals(true, $instance->usernameCheck($username), "create username with one more than the minimum required characters");
    }

    public function testUsernameMaxMinusOne(){
        $instance = new signUpValidator();
        $username = "testusername1234567"; // 19 characters
        $this->assertEquals(true, $instance->usernameCheck($username), "create username with one less than the maximum required characters");
    }

    public function testUsernameNoSpecial(){
        $instance = new signUpValidator();
        $username = "testusername"; // no special characters
        $this->assertEquals(true, $instance->usernameCheck($username), "create username with no special characters");
    }

    public function testUsernameWithNumber(){
        $instance = new signUpValidator();
        $username = "testuser1"; // includes number
        $this->assertEquals(true, $instance->usernameCheck($username), "create username with a number");
    }

    public function testUsernameMore(){
        $instance = new signUpValidator();
        $username = "testusername123456789"; // 21 characters
        $this->assertEquals(false, $instance->usernameCheck($username), "create username with more than the maximum amount of characters allowed");
    }

    public function testUsernameLess(){
        $instance = new signUpValidator();
        $username = "test"; // 4 characters
        $this->assertEquals(false, $instance->usernameCheck($username), "create username with less than the maximum amount of characters allowed");
    }

    public function testUsernameNull(){
        $instance = new signUpValidator();
        $username = ""; //empty
        $this->assertEquals(false, $instance->usernameCheck($username), "create username with no characters");
    }

    public function testUsernameSpecialCharacter(){
        $instance = new signUpValidator();
        $username = "testuser#"; //includes special characters
        $this->assertEquals(false, $instance->usernameCheck($username), "create username with a special character");
    }

    public function testUsernameWhitespace(){
        $instance = new signUpValidator();
        $username = "user name"; // contains whitespace
        $this->assertEquals(false, $instance->usernameCheck($username), "create username with whitespace");
    }

    /*
    TEST CREATING USERNAME THAT ALREADY EXISTS HERE
    Will have to work with the database; likely retrieve data from database, then check that data to ensure username is not taken*/

    //PASSWORD TESTS
    public function testPasswordMin(){
        $instance = new signUpValidator();
        $password = "validpass!"; //10 characters
        $this->assertEquals(true, $instance->passwordCheck($password), "create password with minimum required characters");
    }

    public function testPasswordMax(){
        $instance = new signUpValidator();
        $password = "validpasswordyayyyy!"; //20 characters
        $this->assertEquals(true, $instance->passwordCheck($password), "create password with maximum required characters");
    }

    public function testPasswordMinPlusOne(){
        $instance = new signUpValidator();
        $password = "validpass!!"; // 11 characters
        $this->assertEquals(true, $instance->passwordCheck($password), "create password with one more than the minimum required characters");
    }

    public function testPasswordMaxMinusOne(){
        $instance = new signUpValidator();
        $password = "validpasswordyayyy!"; // 19 characters
        $this->assertEquals(true, $instance->passwordCheck($password), "create password with one less than the maximum required characters");
    }

    public function testPasswordLess(){
        $instance = new signUpValidator();
        $password = "password!"; // 9 characters
        $this->assertEquals(false, $instance->passwordCheck($password), "create password with less than the minimum required characters");
    }

    public function testPasswordMore(){
        $instance = new signUpValidator();
        $password = "validpasswordyayyyyy!"; // 21 characters
        $this->assertEquals(false, $instance->passwordCheck($password), "create password with one more than the maximum required characters");
    }

    public function testPasswordSpecialCharacter(){
        $instance = new signUpValidator();
        $password = "validpassword!"; // contains special character
        $this->assertEquals(true, $instance->passwordCheck($password), "create password with a special character");
    }

    public function testPasswordNoSpecialCharacter(){
        $instance = new signUpValidator();
        $password = "validpassword"; // no special character
        $this->assertEquals(false, $instance->passwordCheck($password), "create password with no special character");
    }

    public function testPasswordWhitespace(){
        $instance = new signUpValidator();
        $password = "valid password"; // contains whitespace
        $this->assertEquals(false, $instance->passwordCheck($password), "create password with whitespace");
    }

    public function testPasswordEmpty(){
        $instance = new signUpValidator();
        $password = ""; //empty 
        $this->assertEquals(false, $instance->passwordCheck($password), "create password with no characters");
    }

    //ID TESTS
    public function testIdCorrectLength(){
        $instance = new signUpValidator();
        $id = 1234567890; // 10 numbers
        $this->assertEquals(true, $instance->idCheck($id), "create id with 10 numbers");
    }

    public function testIdLessLength(){
        $instance = new signUpValidator();
        $id = 123456789; // 9 numbers
        $this->assertEquals(false, $instance->idCheck($id), "create id with 9 numbers");
    }

    public function testIdMoreLength(){
        $instance = new signUpValidator();
        $id = 12345678901; // 11 numbers
        $this->assertEquals(false, $instance->idCheck($id), "create id with 11 numbers");
    }

    public function testIdStringNumbers(){
        $instance = new signUpValidator();
        $id = "1234567890"; // string datatype
        $this->assertEquals(false, $instance->idCheck($id), "create id that is made of string numbers");
    }

    public function testIdNonNumbers(){
        $instance = new signUpValidator();
        $id = "abc#defghi"; //contains non-numerical characters
        $this->assertEquals(false, $instance->idCheck($id), "create id that is made of non numbers");
    }

    public function testIdFloat(){
        $instance = new signUpValidator();
        $id = 123.456789; //floating point datatype
        $this->assertEquals(false, $instance->idCheck($id), "create id that is a floating point number");
    }

    //FUNCTIONS THAT TEST UNIQUENESS ARE DOWN HERE
    public function testUsernameNotUnique(){
        $instance = new signUpValidator();
        $username = "testuser"; // username in database
        $this->assertFalse($instance->verifyUsernameUnique($username, $this->connectToDatabase()));
    }

    public function testUsernameUnique(){
        $instance = new signUpValidator();
        $username = "UNIQUEUSER"; // username not yet in database
        $this->assertTrue($instance->verifyUsernameUnique($username, $this->connectToDatabase()));
    }

    public function testIdNotUnique(){
        $instance = new signUpValidator();
        $id = 1234567890; // id in database
        $this->assertFalse($instance->verifyIdUnique($id, $this->connectToDatabase()));
    }

    public function testIdUnique(){
        $instance = new signUpValidator();
        $id = 9999999999; // id not yet in database
        $this->assertTrue($instance->verifyIdUnique($id, $this->connectToDatabase()));
    }

    public function testNameMin(){
        $instance = new signUpValidator();
        $name = "Wu"; // 2 characters
        $this->assertTrue($instance->nameCheck($name), "create name with minimum required characters");
    }

    public function testNameMaxAndSpecial(){
        $instance = new signUpValidator();
        $name = "abcdefghijklmnopqrstuvwxyz.'-A"; // 30 characters
        $this->assertTrue($instance->nameCheck($name), "create name with maximum required characters, & with special characters covering all that are allowed");
    }

    public function testNameLess(){
        $instance = new signUpValidator();
        $name = "a"; // 1 character
        $this->assertFalse($instance->nameCheck($name), "create name with less than the minimum required characters");
    }

    public function testNameMore(){
        $instance = new signUpValidator();
        $name = ""; // 31 characters
        $this->assertFalse($instance->nameCheck($name), "create name with more than the maximum amount of required characters");
    }

    public function testNameWhitespace(){
        $instance = new signUpValidator();
        $name = " "; // whitespace
        $this->assertFalse($instance->nameCheck($name), "create name with whitespace");
    }

    public function testNameEmpty(){
        $instance = new signUpValidator();
        $name = ""; // 0 characters
        $this->assertFalse($instance->nameCheck($name), "create name with zero characters");
    }

    public function testNameNotAllowedSpecialCharacters(){
        $instance = new signUpValidator();
        $name = "John@Doe"; // Special character that is not allowed
        $this->assertFalse($instance->nameCheck($name), "create name with special characters that are not allowed");
    }

    public function testNameLowerInterior(){
        $instance = new signUpValidator();
        $name = "Bob"; // 3 characters
        $this->assertTrue($instance->nameCheck($name), "create name with one more than the minimum amount of required characters");
    }

    public function testNameUpperInterior(){
        $instance = new signUpValidator();
        $name = "abcdefghijklmnopqrstuvwxyzABC"; // 29 characters
        $this->assertTrue($instance->nameCheck($name), "create name with one less than the maximum amount of required characters");
    }

    public function testNameNumbers(){
        $instance = new signUpValidator();
        $name = "Bob14"; // Includes numbers
        $this->assertFalse($instance->nameCheck($name), "create name with numbers");
    }

    public function testCheckParentAccountExists(){
        $instance = new signUpValidator();
        $username = "testuser";
        $this->assertFalse($instance->checkParentAccountExists($username, $this->connectToDatabase()), "check if parent account with specified username exists");
    }

    //SQL INJECTION TESTS 

    public function testUsernameSqlInjection(){
        $instance = new signUpValidator();
        $username = "; DROP TABLE ACCOUNTDETAILS;";
        $this->assertTrue($instance-> verifyUsernameUnique($username, $this->connectToDatabase()), "attempt SQL injection through Username Field");
    }

    public function testFirstnameSqlInjection(){
        $instance = new signUpValidator(); 
        $password = "; DROP TABLE Users;";
        $this->assertFalse($instance->passwordCheck($password, $this->connectToDatabase()), "attempt SQL injection through Password Field");
    }

    public function testLastnameSqlInjection(){
        $instance = new signUpValidator(); 
        $lastname = "; DROP TABLE Users;";
        $this->assertFalse($instance->nameCheck($lastname), "attempt SQL injection through Lastname Field");
    }

    public function testIdSqlInjection(){
        $instance = new signUpValidator(); 
        $id = "; DROP TABLE Users;";
        $this->assertFalse($instance->idCheck($id, $this->connectToDatabase()), "attempt SQL injection through ID Field");
    }


    public function testRoleSQLInjection(){
        $instance = new signUpValidator(); 
        $role = "; DROP TABLE Users;";
        $this->assertFalse($instance->checkParentAccountExists($role, $this->connectToDatabase()), "attempt SQL injection through Role Field");
    }

    public function testPasswordSQLInjection(){
        $instance = new signUpValidator(); 
        $password = "; DROP TABLE ACCOUNTDETAILS;";
        $this->assertFalse($instance->passwordCheck($password, $this->connectToDatabase()), "attempt SQL injection through Password Field");
    }

}

?>