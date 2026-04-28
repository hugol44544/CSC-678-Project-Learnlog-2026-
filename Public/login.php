<?php
include_once '../src/Auth.php';
include_once '../config/config.php';

session_start();

// Only run login logic AFTER form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call loginUser with ALL required arguments
    $user = loginUser($conn, $username, $password);

    if ($user) {
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['role'] = $user['role'];

     header("Location: dashboard.php");
    exit();
    } else {
        echo "Invalid login credentials. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/styles.css">
    <title>Login</title>
  </head>
  <body>
    <div class="login-container">
      <h2>Login</h2>
      <h3>Log in to your account</h3>
      <form action="login.php" method="POST">


<div class="form-group">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" />
</div>

<div class="form-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required />
</div>

<button type="submit">Login</button>
</form>

<div class = "auth-link"> Dont have an account ? <a href="SignUp.php">Sign up</a>
    </div>
  </body>
</html>
