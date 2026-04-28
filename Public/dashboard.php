<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

include_once __DIR__ . '/../src/Auth.php';
include_once __DIR__ . '/../src/classManagement.php';
include_once __DIR__ . '/../src/assignmentManagement.php';
include_once __DIR__ . '/../config/config.php';

#If the user is a student, and they try to join the class, the following code executes
if (isset($_POST['submitJoinClass'])){
    $studentid = $_SESSION['userid'];
    $classid = $_POST['ClassId'];
    $joined = joinClass($conn, $studentid, $classid);
    $assigned = getAssignmentsUponJoining($conn, $studentid, $classid);
    if($joined && $assigned){
        header("Location: " . $_SERVER['PHP_SELF']);
    }else{
        echo "Error joining class";
    }
}

#If the user is a student, and tries to change their assignment status, the following code executes
if(isset($_POST['changeStatus'])){
    $studentid = $_SESSION['userid'];
    $assignmentid = $_POST['assignmentId'];
    $newStatus = $_POST['status'];
    $updateStatus = changeAssignmentStatus($conn, $studentid, $assignmentid, $newStatus);

    if($updateStatus){
        header("Location: dashboard.php");
        exit();
    }else{
        echo "Assignment status change failed";
    }

}

$role = $_SESSION['role'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];

$classes = [];
if ($role === 'Teacher') {
    $classes = getTeacherClasses($conn, $_SESSION['userid']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard-body">

    <nav class="navbar">
        <span class="navbar-brand">📚 Student Track</span>
        <div class="navbar-right">
            <span class="navbar-user"><?php echo htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']); ?></span>
            <form action="logOut.php" method="POST">
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?>! 🌟</h2>
        </div>

        <?php if ($role === "Student"): ?>
        <div class="card">
            <h3>My Assignments</h3>
            <div class = "container">
                <?php echo displayAssignmentsForStudent($conn, $_SESSION['userid']);?>
            </div>
        </div>

        <div class="card">
            <h3>My Classes</h3>
            <div class = "container">
                <?php echo displayClassesEnrolledIn($conn, $_SESSION['userid']);?>
            </div>
            <p>Below are a list of classes you can join.</p>
            <div class = "container">
                <?php echo displayClassesToJoin($conn, $_SESSION['userid']);?>
            </div>
        </div>
        <?php elseif ($role === "Teacher"): ?>
           
            <div class="card">
                <h3>My Classes</h3>
                <?php if (empty($classes)): ?>
                    <p>No classes yet.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $class): ?>
                            <tr>
                                <td><?= htmlspecialchars($class['name']) ?></td>
                                <td><?= $class['student_count'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <a href="createClass.php" class="btn-primary-link">+ Create Class</a>
                <?php if (!empty($classes)): ?>
                    <a href = "createAssignment.php" class = "btn-primary-link">+ Create Assignment</a>
                <?php endif; ?>
            </div>

        <?php elseif ($role === "Parent"): ?>
        <div class="card">
            <h3>My Children</h3>
            <div class = "container">
                <?php echo displayKid($conn, $_SESSION['userid']);?>
            </div>
            <br><a href = "childSignUp.php">Link Kid (Create new Child Account)</a>
        </div>
    
       
    
            <?php endif; ?>

</body>
</html>