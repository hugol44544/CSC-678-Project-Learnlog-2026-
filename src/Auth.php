<?php
# functions for signing up and logging in.
#createUser($con, $firstname, $lastname, $role, $usernames $password, $id)
#loginUsers($con, $username, $password)


//creates a user and adds them to the database, returns true if successful, otherwise false
function createUser($conn, $firstname, $lastname, $role, $studentid, $username, $password) {

    // Insert into Users
    $stmt = $conn->prepare(
        "INSERT INTO Users (firstname, lastname, role, studentid) VALUES (?, ?, ?, ?)"
    );
    
    $stmt->bind_param("sssi", $firstname, $lastname, $role, $studentid);
    $stmt->execute();

    $userid = $stmt->insert_id;

    // Insert into AccountDetails
    $stmt = $conn->prepare(
        "INSERT INTO AccountDetails (userid, username, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("iss", $userid, $username, $password);
    $stmt->execute();

    return $userid;
}


//returns user data if login is successful, otherwise returns null
function loginUser($conn, $username, $password) {
    $stmt = $conn->prepare(
        "SELECT Users.userid, Users.firstname, Users.lastname, Users.role  
         FROM Users
         JOIN AccountDetails ON Users.userid = AccountDetails.userid
         WHERE  AccountDetails.username = ?
           AND AccountDetails.password = ?"
    );

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function linkKid($conn, $parentid, $studentid){
    // Insert into junction table ParentsAndStudents
    $stmt = $conn->prepare(
        "INSERT INTO ParentStudent (parentid, studentid) VALUES (?, ?)"
    );

    $stmt->bind_param("ii", $parentid, $studentid);
    $stmt->execute();

}

function displayKid($conn, $parentid){
    $stmt = $conn->prepare("SELECT Users.userid, Users.firstname, Users.lastname, Users.studentid, AccountDetails.username FROM Users INNER JOIN ParentStudent ON Users.userid = ParentStudent.studentid INNER JOIN AccountDetails ON AccountDetails.userid = Users.userid WHERE ParentStudent.parentid = ?;");
    $stmt->bind_param("i", $parentid);
    $stmt->execute();
    $result = $stmt->get_result();
    $build = "";
    if($result->num_rows == 0){
        $build = "<p>No children linked yet.</p>";
    } else {
        while($data = $result->fetch_assoc()){
            $build .= "<a href='childView.php?studentid={$data['userid']}' style='text-decoration:none;'>";
            $build .= "<div class='containerCard'>";
            $build .= "<h2>{$data['firstname']} {$data['lastname']}</h2>";
            $build .= "<p>Student Id: {$data['studentid']}</p>";
            $build .= "<p>Username: {$data['username']}</p>";
            $build .= "</div>";
            $build .= "</a>";
        }
    
    return $build;
}

}