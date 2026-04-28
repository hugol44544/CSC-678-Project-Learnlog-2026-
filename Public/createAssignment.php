<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once __DIR__ . '/../src/classManagement.php';
include_once __DIR__ . '/../src/assignmentManagement.php';
include_once __DIR__ . '/../config/config.php';

session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$classes = getTeacherClasses($conn, $_SESSION['userid']);

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $assignmentName = $_POST['assignmentname'];  
    $description = $_POST['desc'];
    $classid = $_POST['classID'];

    // Validate the input
    $errors = [];  // array to hold error messages

    if (!assignmentNameCheck($assignmentName)) {
        echo "<p>Assignment name must be between 3 & 60 characters and must not be empty.</p>";
        exit;
    }

    if (!assignmentDescriptionCheck($description)) {
        echo "<p>Assignment description cannot exceed 200 characters.</p>";
        exit;
    }
    
    $newAssignment = createAssignment($conn, $assignmentName, $description, $classid);

    if ($newAssignment) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error creating assignment.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title> Create Assignment </title>
</head>
<body>
    <body>
        <div class="SignUp-container">
          <h2>Create New Assignment</h2>
          <form action="createAssignment.php" method="POST">
    <div class="form-group">
        <label for="assignmentname">Assignment Name</label>
        <input type="text" id="assignmentname" name="assignmentname" required />
    </div>
    <div class="form-group">
        <label for="desc">Description</label>
        <input type="text" id="desc" name="desc"/>
    </div>
    <div class="form-group">
        <label for="class">Class:</label>
        <select id="class" name="classID" required>
        <option value = ""> Select the class the assignment is for</option>
        <?php foreach ($classes as $class): ?>
        <option value = <?= $class['classid']?>><?= htmlspecialchars($class['name'])?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <button type="submit">Create Assignment</button>
</form>
        </div>
</body>
</html>