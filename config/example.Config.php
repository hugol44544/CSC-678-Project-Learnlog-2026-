<?php

$db_host = "YOUR_HOST_HERE";
$db_user = "YOUR_USERNAME_HERE";
$db_pass = "YOUR_PASSWORD_HERE";
$db_name = "railway";
$db_port = 12345;

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//THIS IS AN EXAMPLE OF THE CONFIG FILE, PLEASE FILL IN THE DETAILS ABOVE TO CONNECT TO THE DATABASE