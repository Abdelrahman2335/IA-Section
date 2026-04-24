<?php
session_start();
include "db.php";

// Security
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get ID
$id = $_GET['id'];

// Get student data
$result = $conn->query("SELECT * FROM students WHERE id=$id");
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

<h2>Edit Student</h2>

<form action="update_student.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo $student['name']; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $student['email']; ?>" required><br><br>

    <label>Department:</label><br>
    <select name="department">
        <option value="CS" <?php if($student['department']=="CS") echo "selected"; ?>>CS</option>
        <option value="IT" <?php if($student['department']=="IT") echo "selected"; ?>>IT</option>
    </select><br><br>

    <button type="submit">Update</button>

</form>

</body>
</html>
</html>
</html>