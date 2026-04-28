<?php

require __DIR__ . '/../src/assignmentManagement.php';

use PHPUnit\Framework\TestCase;

class assignmentTests extends TestCase{

    //assignment name tests
    function testAssignmentNameMin(){
        $assignmentname = "Hw1"; //3 characters
        $this->assertTrue(assignmentNameCheck($assignmentname), "create assignment name containing the minimum amount of required characters");
    }

    function testAssignmentNameMax(){
        $assignmentname = "HWabcdefghijklmnopqrstuvwxyz -1234567890abcdefghjikmnbvcd[]."; //60 characters
        $this->assertTrue(assignmentNameCheck($assignmentname), "create assignment name containing the maximum amount of required characters");
    }

    function testAssignmentNameMaxMinusOne(){
        $assignmentname = "HWabcdefghijklmnopqrstuvwxyz -1234567890abcdefghjikmnbvcd[]"; //59 characters
        $this->assertTrue(assignmentNameCheck($assignmentname), "create assignment name containing one less than the maximum amount of required characters");
    }

    function testAssignmentNameMinPlusOne(){
        $assignmentname = "HW-1"; //4 characters
        $this->assertTrue(assignmentNameCheck($assignmentname), "create assignment name containing one more than the minimum amount of required characters");
    }

    function testAssignmentNameTooShort(){
        $assignmentname = "HW"; //2 characters
        $this->assertFalse(assignmentNameCheck($assignmentname), "create assignment name containing less than the minimum amount of required characters");
    }

    function testAssigmentNameTooLong(){
        $assignmentname = "HWabcdefghijklmnopqrstuvwxyz -1234567890abcdefghjikmnbvcd[]!a"; //61 characters
        $this->assertFalse(assignmentNameCheck($assignmentname), "create assignment name containing more than the maximum amount of required characters");
    }

    function testAssigmentNameEmpty(){
        $assignmentname = ""; //0 characters
        $this->assertFalse(assignmentNameCheck($assignmentname), "create assignment name with name containing no characters");
    }

    function testAssigmentNameWhitespace(){
        $assignmentname = "          "; //Only whitespace
        $this->assertFalse(assignmentNameCheck($assignmentname), "create assignment name with name containing only whitespace");
    }

    function testAssignmentDescriptionMax(){
        $desc = "8v!zP Q2@7m*R [L9#sX fG5&u1 )Yt0^ wK4ppp bH6%v9 iZ2+aM pQ7|rE dS1!oW xC3{kT yU8]nB lI5~vF gJ9?qH mZ2:eX rP4<wL oA7>sK nV1/zD tM8.iU bG5_pR qY2*fJ eW9=nH uC3(kO xL6)mZ sI4@vB pQ8#rT jG2^yU kM5!dW lO1&z"; //200 characters
        $this->assertTrue(assignmentDescriptionCheck($desc), "create description containing the maximum amount of required characters");
    }

    function testAssignmentDescriptionMaxMinusOne(){
        $desc = "8v!zP Q2@7m*R [L9#sX fG5&u1 )Yt0^ wK4ppp bH6%v9 iZ2+aM pQ7|rE dS1!oW xC3{kT yU8]nB lI5~vF gJ9?qH mZ2:eX rP4<wL oA7>sK nV1/zD tM8.iU bG5_pR qY2*fJ eW9=nH uC3(kO xL6)mZ sI4@vB pQ8#rT jG2^yU kM5!dW lO1&z"; //199 characters
        $this->assertTrue(assignmentDescriptionCheck($desc), "create description containing one less than the maximum amount of required characters");
    }

    function testAssigmentDescriptionTooLong(){
        $desc = "8v!zP Q2@7m*R [L9#sX fG5&u1 )Yt0^ wK4ppp bH6%v9 iZ2+aM pQ7|rE dS1!oW xC3{kT yU8]nB lI5~vF gJ9?qH mZ2:eX rP4<wL oA7>sK nV1/zD tM8.iU bG5_pR qY2*fJ eW9=nH uC3(kO xL6)mZ sI4@vB pQ8#rT jG2^yU kM5!dW lO1&zSb"; //201 characters
        $this->assertFalse(assignmentDescriptionCheck($desc), "create description containing more than the maximum amount of required characters");
    }

    function testAssigmentDescriptionEmpty(){
        $desc = ""; //0 characters
        $this->assertTrue(assignmentDescriptionCheck($desc), "create assignment description containing no characters");
    }

    function testAssigmentDescriptionWhitespace(){
        $desc = "          "; //Only whitespace
        $this->assertTrue(assignmentDescriptionCheck($desc), "create assignment description containing only whitespace");
    }

}

?>