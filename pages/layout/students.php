<?php
include("config/ConfigDB.php");

$SelectQuery = "SELECT * FROM students";
$ExecuteQuery = mysqli_query($conn, $SelectQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>Students List</h2>

    <a href="#" class="btn btn-primary mb-3">Add Student</a>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>

        <?php
        while($Data = mysqli_fetch_array($ExecuteQuery)){
        ?>

        <tr>
            <td><?php echo $Data['id']; ?></td>
            <td><?php echo $Data['name']; ?></td>
            <td><?php echo $Data['email']; ?></td>
            <td><?php echo $Data['department']; ?></td>

            <td>
                <a href="#" class="btn btn-warning">Edit</a>
                <a href="#" class="btn btn-danger">Delete</a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>