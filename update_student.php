<?php
session_start();
include "db.php";

// Security
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get data
$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$department = $_POST['department'];

// Update query
$sql = "UPDATE students 
        SET name='$name', email='$email', department='$department' 
        WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: students.php?msg=updated");
} else {
    echo "Error: " . $conn->error;
}
?>