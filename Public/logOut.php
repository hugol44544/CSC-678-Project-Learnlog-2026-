<?php 
// log out page 

session_start(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page after logout
    header("Location: login.php");
    exit();
}



?> 