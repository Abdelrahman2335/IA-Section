<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (!empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: students.php?msg=deleted");
        exit();
    } else {
        header("Location: students.php?msg=error");
        exit();
    }

    $stmt->close();
} else {
    header("Location: students.php?msg=invalid");
    exit();
}
?>