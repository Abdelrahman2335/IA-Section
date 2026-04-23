<?php

// $studentObj = new Student();
$allStudents = [];


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

    <table class="table table-bordered">

        <tr>
            <th>id<th>Name</th>
            <th>email</th>
            <th>department</th>
            
        </tr>

        <?php foreach ($allStudents as $student): ?>

        <tr>
            <td><?php echo $student['name']; ?></td>
            <td><?php echo $student['email']; ?></td>
            <td><?php echo $student['department']; ?></td>

            <td>
                <a href="?id=<?php echo $student['id']; ?>" class="btn btn-danger">Delete</a>
            </td>
        </tr>

        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
