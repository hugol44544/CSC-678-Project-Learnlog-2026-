<?php

require_once '../config/config.php';

if($conn) {
    echo "Connection successful!";
} else {
    echo "Connection failed: " . $conn->connect_error;
}