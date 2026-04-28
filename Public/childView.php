<?php 

session_start();
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Parent') {
    header("Location: login.php");
    exit();
}

include_once __DIR__ . '/../src/Auth.php';
include_once __DIR__ . '/../config/config.php';

$studentid = isset($_GET['studentid']) ? (int)$_GET['studentid'] : 0;
 
if ($studentid === 0) {
    header("Location: dashboard.php");
    exit();
}
 
// Get child's info
$stmt = $conn->prepare(
    "SELECT Users.firstname, Users.lastname, Users.studentid 
    FROM Users 
    INNER JOIN ParentStudent ON Users.userid = ParentStudent.studentid 
    WHERE ParentStudent.parentid = ? AND Users.userid = ?"
);
$stmt->bind_param("ii", $_SESSION['userid'], $studentid);
$stmt->execute();
$child = $stmt->get_result()->fetch_assoc();

if (!$child) {
    header("Location: dashboard.php");
    exit();
}

//get childs assignments 

$stmt2 = $conn->prepare(
    "SELECT Assignments.name, Assignments.description, Classes.name AS classname,
    COALESCE(StudentAssignment.status, 'Not Started') AS status
    FROM StudentClass
    JOIN Assignments ON StudentClass.classid = Assignments.classid
    JOIN Classes ON Classes.classid = StudentClass.classid
    LEFT JOIN StudentAssignment ON StudentAssignment.assignmentid = Assignments.id 
    AND StudentAssignment.studentid = ?
    WHERE StudentClass.studentid = ?"
);
$stmt2->bind_param("ii", $studentid, $studentid);
$stmt2->execute();
$assignments = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($child['firstname']) ?>'s Assignments</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard-body">
 
    <nav class="navbar">
        <span class="navbar-brand">📚 Student Tracker</span>
        <div class="navbar-right">
            <span class="navbar-user"><?= htmlspecialchars($_SESSION['firstname'] . " " . $_SESSION['lastname']) ?></span>
            <form action="logOut.php" method="POST">
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>
    </nav>
 
    <div class="dashboard-container">
 
        <div class="dashboard-header">
            <h2><?= htmlspecialchars($child['firstname'] . " " . $child['lastname']) ?>'s Dashboard</h2>
            <p>Student ID: <?= htmlspecialchars($child['studentid']) ?></p>
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
 
        <div class="card">
            <h3>Assignments</h3>
            <?php if (empty($assignments)): ?>
                <p>No assignments yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Class</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['name']) ?></td>
                            <td><?= htmlspecialchars($a['classname']) ?></td>
                            <td><?= htmlspecialchars($a['description']) ?></td>
                            <td><span class="status-tag status-<?= strtolower(str_replace(' ', '-', $a['status'])) ?>">
                                <?= htmlspecialchars($a['status']) ?>
                            </span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
 
    </div>
 
</body>
</html>





