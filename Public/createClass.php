<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once __DIR__ . '/../src/classManagement.php';
include_once __DIR__ . '/../config/config.php';

session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $classname = $_POST['classname'];  
    $teacherid = (int)$_SESSION['userid'];

    // Validate the input
    $errors = [];  // array to hold error messages

    if (!classNameCheck($classname)) {
        echo "<p>Class name must be between 3 & 40 characters.</p>";
        exit;
    }
    
    #creating new class
    $newClass = createClass($conn, $classname, $teacherid);

    #validating if the process was successful or not
    if ($newClass) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error creating class.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title> Create Class </title>
</head>
<body>
    <body>
        <div class="SignUp-container">
          <h2>Create New Class</h2>
          <form action="createClass.php" method="POST">
    <div class="form-group">
        <label for="classname">Class Name</label>
        <input type="text" id="classname" name="classname" required />
    </div>

    <button type="submit">Create Class</button>
</form>
        </div>
</body>
</html>