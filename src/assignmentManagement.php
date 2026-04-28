<?php

#Determines if an assignment's name is valid or not. Valid assignment names must be >= 3 characters but <= 60 characters & must not be only whitespace.
function assignmentNameCheck($assignmentName): bool{
    $valid = strlen($assignmentName) >= 3 && strlen($assignmentName) <= 60 && !ctype_space($assignmentName);
    return $valid;
}

#Determines if an assignment's description is valid or not. Valid descriptions must be less than 200 characters in length.
function assignmentDescriptionCheck($assignmentDescription): bool{
    $valid = strlen($assignmentDescription) <= 200;
    return $valid;
}

#Creates a new assignment & adds it to the database.
function createAssignment($conn, $assignmentname, $description, $classid){
    $stmt = $conn->prepare("INSERT INTO Assignments (name, description, classid) VALUES (?, ?, ?);");
    $stmt->bind_param("ssi", $assignmentname, $description, $classid);
    $stmt->execute();

    $assignmentid = $stmt->insert_id;
    return assignAssignment($conn, $classid, $assignmentid);
}

#Assigns assignments to students in the class automatically when the assignment is created.
function assignAssignment($conn, $classid, $assignmentid): bool{
    $stmt = $conn->prepare("SELECT studentid FROM StudentClass WHERE classid = ?;");
    $stmt->bind_param("i",$classid);
    $stmt->execute();
    $result = $stmt->get_result();

    $studentid = 0;
    $stmt = $conn->prepare("INSERT INTO StudentAssignment (studentid, assignmentid) VALUES (?, ?)");
    $stmt->bind_param("ii",$studentid,$assignmentid);
    $success = true;

    while($students = $result->fetch_assoc()){
        $studentid = $students['studentid'];
        if(!$stmt->execute()){
            $success = false;
        }
    }

    return $success;

}

#Function that displays a student's assignments. Provides the infrastructure for a student to update their assignment progress.
function displayAssignmentsForStudent($conn, $studentid){

    //get all the relevant info related to an assignment
    $stmt = $conn->prepare("SELECT StudentAssignment.assignmentid, StudentAssignment.status, Assignments.name AS assignmentname, Assignments.description, Classes.name AS classname, Classes.teacherid, Users.firstname, Users.lastname FROM StudentAssignment INNER JOIN Assignments ON StudentAssignment.assignmentid = Assignments.id INNER JOIN Classes ON Assignments.classid = Classes.classid INNER JOIN Users ON Classes.teacherid = Users.userid WHERE StudentAssignment.studentid = ?;");
    $stmt->bind_param("i",$studentid);
    $stmt->execute();
    $result = $stmt->get_result();

    //dynamically generates HTML; includes POST elements to retrieve data, providing the infrastrcuture needed to change assignment status
    $build = "";
    $statuses = ["Not Started","In Progress","Completed"];
    
    while($data = $result->fetch_assoc()){
        $build .= "<div class = 'containerCard'>";
        $build .= "<h3>{$data['assignmentname']}</h3>";
        $build .= "<p>{$data['description']}</p>";
        $build .= "<p>Class: {$data['classname']}</p>";
        $build .= "<p>Teacher: {$data['firstname']} {$data['lastname']}</p>";
        $build .= "<p>Assignment Status: {$data['status']}</p>";
        $build .= "<form method='POST' action=''>"; 
        $build .= "<label for='status'>Change Status </label>";
        $build .= "<select id='status' name='status'>";
        foreach($statuses as $currentStatus){
            $selected = ($data['status'] == $currentStatus) ? 'selected' : '';
            $build .= "<option value='$currentStatus' $selected>$currentStatus</option>";
        }
        $build .= "</select>";
        $build .= "<input type='hidden' name='assignmentId' value='{$data['assignmentid']}'>";
        $build .= "<button type='submit' name='changeStatus'>Edit Status</button>";
        $build .= "</form>";
        $build .= "</div>";
    }

    return $build;

}

#Allows the student to change their assignment progress; updates the database.
function changeAssignmentStatus($conn, $studentid, $assignmentid, $assignmentStatus){
    $stmt = $conn->prepare("UPDATE StudentAssignment SET status = ? WHERE studentid = ? AND assignmentid = ?;");
    $stmt->bind_param("sii",$assignmentStatus, $studentid, $assignmentid);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

//assigns students who join a class all of the class' existing assignments
function getAssignmentsUponJoining($conn, $studentid, $classid){
    // get all assignments from the class
    $stmt = $conn->prepare("SELECT id FROM Assignments WHERE classid = ?;");
    $stmt->bind_param("i",$classid);
    $stmt->execute();
    $result = $stmt->get_result();

    //assign students all assignments from the class
    $assignmentid = 0;
    $stmt = $conn->prepare("INSERT INTO StudentAssignment (studentid, assignmentid) VALUES (?, ?)");
    $stmt->bind_param("ii",$studentid,$assignmentid);
    $success = true;

    while($ids = $result->fetch_assoc()){
        $assignmentid = $ids['id'];
        if (!$stmt->execute()) {
            $success = false;
        }
    }

    return $success;
}

?>