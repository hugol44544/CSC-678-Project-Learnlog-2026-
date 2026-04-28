<?php
// This file handles user registration by validating input and creating a new user in the database.
// note that only students will have a student id.
include_once __DIR__ . '/../src/Auth.php';
include_once __DIR__ . '/../src/signUpValidation.php';
include_once __DIR__ . '/../config/config.php';

// Create validator object
$validator = new SignUpValidator();

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = $_POST['firstname'];  
    $lastname = $_POST['lastname'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

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

    // If validation fails, stop here
    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p>" . htmlspecialchars($e) . "</p>";
        }
        exit;
    }

    // Create the user if validation passes
    $userId = createUser($conn, $firstname, $lastname, $role, null, $username, $password);

    if ($userId) {
        header("Location: login.php");
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
    <title> Sign In Page </title>
</head>
<body>
    <body>
        <div class="SignUp-container">
          <h2>Sign Up</h2>
          <h3>Create a new account to continue</h3>
          <form action="SignUp.php" method="POST">
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required />
    </div>

    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" required />
    </div>

    <div class="form-group">
        <label for="role">I am a...</label>
        <select id="role" name="role" required>
        <option value = ""> Select your role </option>
        <option value="Teacher">I'm a Teacher</option>
        <option value="Parent">I'm a Parent</option>
</select>
    </div>

    <div class="form-group">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required />
</div> 
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
    </div>

    <button type="submit">Create Account</button>
</form>

<div class="auth-link">Already have an account? <a href="login.php">Log In</a>
  </div>
        </div>
</body>
</html>