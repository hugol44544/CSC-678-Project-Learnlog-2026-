<?php

include_once __DIR__ . '/../src/Auth.php';
include_once __DIR__ . '/../src/signUpValidation.php';
include_once __DIR__ . '/../config/config.php';

session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Create validator object
$validator = new SignUpValidator();

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = $_POST['firstname'];  
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $studentid = (int)$_POST['studentid'];
    $parentid = (int)$_SESSION['userid'];

    // Validate the input
    $errors = [];  // array to hold error messages

    if (!$validator->usernameCheck($username)) {
        $errors[] = "Username must be 5-20 characters long, contain no spaces, and only letters and numbers.";
    }

    if (!$validator->passwordCheck($password)) {
        $errors[] = "Password must be 10-20 characters long, contain no spaces, and include at least one special character.";
    }

    if (!$validator->verifyUsernameUnique($username, $conn)) {
        $errors[] = "Username is taken.";
    }

    if (!$validator->idCheck($studentid)) {
        $errors[] = "Student ID must be a positive integer with exactly 10 digits.";
    }

    if (!$validator->verifyIdUnique($studentid, $conn)) {
        $errors[] = "Student ID is taken; it must be unique.";
    }

    // If validation fails, stop here
    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p>" . htmlspecialchars($e) . "</p>";
        }
        exit;
    }

    // Create the user if validation passes
    $role = 'Student';
    
    $userId = createUser($conn, $firstname, $lastname, $role, $studentid, $username, $password);

    if ($userId) {

        linkKid($conn, $parentid, $userId); //userid is the primary key 
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error creating user.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title> Create Child Account </title>
</head>
<body>
    <body>
        <div class="SignUp-container">
          <h2>Create Child Account</h2>
          <h3>Create your child's account</h3>
          <form action="childSignUp.php" method="POST">
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required />
    </div>

    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" required />
    </div>

    <div class="form-group">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required />
</div> 
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
    </div>
    <div class="form-group">
        <label for="studentid">Student Id</label>
        <input type="text" id="studentid" name="studentid" required />
    </div>

    <button type="submit">Create Account</button>
</form>

<!--<div class="auth-link">Already have an account? <a href="login.php">Log In</a>
  </div>-->
        </div>
</body>
</html>