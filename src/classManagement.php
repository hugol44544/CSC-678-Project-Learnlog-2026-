<?php

#Determines if the name of a class is valid. Valid classnames must have a length >= 3 but <= 40, and must not be only whitespace characters.
function classNameCheck($classname): bool{
    $valid = strlen($classname) >= 3 && strlen($classname) <= 40 && ctype_space($classname) == false;
    return $valid;
}

#Creates a class; adds it to the database. If successful, returns true.
function createClass($conn, $classname, $teacherid){
    $stmt = $conn->prepare("INSERT INTO Classes (name, teacherid) VALUES (?, ?);");
    $stmt->bind_param("si", $classname, $teacherid);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

#Gets all the classes a student can join. The student must not already be in the class.
#Dynamically generates HTML using php; includes POST action to allow class joining.
function displayClassesToJoin($conn,$studentid){
    $stmt = $conn->prepare("SELECT Classes.classid, Classes.name, Users.firstname, Users.lastname FROM Classes INNER JOIN Users ON Classes.teacherid = Users.userid WHERE Classes.classid NOT IN (SELECT classid FROM StudentClass WHERE studentid = ?);");
    $stmt->bind_param("i",$studentid);
    $stmt->execute();
    $result = $stmt->get_result();

    $build = "";
    while($data = $result->fetch_assoc()){
        $build .= "<div class = 'containerCardClassesToJoin'>";
        $build .= "<h3>{$data['name']}</h3>";
        $build .= "<p>Teacher: {$data['firstname']} {$data['lastname']}</p>";
        $build .= "<form method='POST' action=''>"; 
        $build .= "<input type='hidden' name='ClassId' value='{$data['classid']}'>";
        $build .= "<button type='submit' name='submitJoinClass'>Join Class</button>";
        $build .= "</form>";
        $build .= "</div>";
    }

    return $build;
}

#Displays all the classes a student is enrolled in.
function displayClassesEnrolledIn($conn, $studentid){
    $stmt = $conn->prepare("SELECT Classes.name, firstname, lastname FROM Classes INNER JOIN Users ON Classes.teacherid = Users.userid INNER JOIN StudentClass ON Classes.classid = StudentClass.classid WHERE StudentClass.studentid = ?;");
    $stmt->bind_param("i",$studentid);
    $stmt->execute();
    $result = $stmt->get_result();

    $build = "";
    while($data = $result->fetch_assoc()){
        $build .= "<div class = 'containerCardClassesToJoin'>";
        $build .= "<h3>{$data['name']}</h3>";
        $build .= "<p>Teacher: {$data['firstname']} {$data['lastname']}</p>";
        $build .= "</div>";
    }

    if($build == ""){
        $build = "No classes enrolled in yet.";
    }

    return $build;
}

#Updates the database to allow a user to join the class.
function joinClass($conn, $studentid, $classid){
    // Insert into StudentClass
    $stmt = $conn->prepare("INSERT INTO StudentClass (studentid, classid) VALUES (?, ?)");
    $stmt->bind_param("ii", $studentid, $classid);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

#Gets all the classes a teacher is in.
function getTeacherClasses($conn, $teacherid) {
    $stmt = $conn->prepare(
        "SELECT Classes.classid, Classes.name,
        COUNT(StudentClass.studentid) AS student_count
        FROM Classes
        LEFT JOIN StudentClass ON Classes.classid = StudentClass.classid
        WHERE Classes.teacherid = ?
        GROUP BY Classes.classid"
    );
    $stmt->bind_param("i", $teacherid);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

?>