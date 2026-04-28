<?php

#Function that directly adds account to the database if username, password, & id are valid.
#NOT COMPLETE YET. Must still test for uniqueness of username & id (make 2 separate functions for determining username & id uniqueness)
class signUpValidator{

  #Function that checks if the username entered follows all the requirements.
  public function usernameCheck($username): bool{
      $usernameCheck = strlen($username) >= 5 && strlen($username) <= 20 && !str_contains($username," ");
      $lettersAndNums = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
      #Loop that uses $lettersAndNums variable; checks if any characters in the username is NOT any of the valid characters that are part of $lettersAndNums
      $i = 0;
      while($i < strlen($username) && $usernameCheck){
        $usernameCheck = str_contains($lettersAndNums,$username[$i]) && $usernameCheck;
        $i++;
      }

      return $usernameCheck;

  }

  #Function that checks if the entered password follows all the requirements
  public function passwordCheck($password): bool{
      $passwordCheck = strlen($password) >= 10 && strlen($password) <= 20 && !str_contains($password, " ");
      $specialCharacters = "~!@#$%^&*()_-+={[}]|\:;<,>.?/'" . chr(34);
      $specialCheck = false;
      #Loop that ensures that the password includes a special character
      for($i = 0;$i < strlen($specialCharacters);$i++){
        if(str_contains($password,$specialCharacters[$i])){
            $specialCheck = true;
        }
      }

      return $passwordCheck && $specialCheck;

  }

  #Function that ensures the entered school id follows all the requirements.
  public function idCheck($id): bool{
      $idCheck = true;
      if(gettype($id) != "integer" || strlen((string)$id) != 10 || $id < 0){
        $idCheck = false;
      }
      return $idCheck;
  }

  #Function that determines if username is unique (not in database) or not
  public function verifyUsernameUnique($username, $conn): bool{

    $stmt = $conn->prepare("SELECT COUNT(1) FROM AccountDetails WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];
    
    return $count == 0;
  }

  #Function that determines if userid is unique (not in database) or not
  public function verifyIdUnique($id, $conn): bool{

    $stmt = $conn->prepare("SELECT COUNT(1) FROM Users WHERE studentid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];
    
    return $count == 0;
  }
  
  #Function that determines if name is valid or not; works for both first & last name
  public function nameCheck($name): bool{
    $nameCheck = strlen($name) >= 2 && strlen($name) <= 30;
    $allowedCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.-";
    for($i = 0;$i < strlen($name);$i++){
        $nameCheck = str_contains($allowedCharacters,$name[$i]) && $nameCheck;
      }
    return $nameCheck;
  }

  #Function that determines if a parent account already exists in the database
  public function checkParentAccountExists($accountUsername, $conn){
    $statement = $conn->query("SELECT COUNT(1) FROM Users INNER JOIN AccountDetails ON AccountDetails.userid = Users.userid WHERE username = '$username' AND `role` = 'Parent';");
    $count = $statement->fetch_row()[0];

    return $count == 1;
  }

  

}

?>