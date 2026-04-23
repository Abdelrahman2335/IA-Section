<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';


$auth = new \App\Auth();
$auth->logOut();

$home = new \App\Home();
$allCourses = $home->getCourses();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="../../assets/css/home.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="card card-custom shadow p-4">

            <h3 class="mb-3">Courses List</h3>


            <div class="text-start mt-3 mb-3">
                <button class="btn btn-sm pink-btn" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    Add Course
                </button>
            </div>

            <!-- Table -->
            <table class="table table-bordered table-hover bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Course Hours</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="courseTable">

                    <tr>
                        <td>1</td>
                        <td>Math</td>
                        <td>3</td>
                        <td>
                            <button onclick="deleteRow(this)" style="border:none; background:none; font-size:18px;">
                                🗑
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Physics</td>
                        <td>4</td>
                        <td>
                            <button onclick="deleteRow(this)" style="border:none; background:none; font-size:18px;">
                                🗑
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>


            <div class="text-end mt-3">
                <a href="?logout=1" class="btn btn-sm pink-btn">
                    Logout
                </a>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCourseModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                                        <select id="courseSelect" class="form-control mb-2">
                                                <option value="">Select Course</option>

                                                <?php foreach ($allCourses as $course): ?>
                                                        <option value="<?= htmlspecialchars($course['course_name'] ?? '') ?>" data-hours="<?= (int)($course['hours'] ?? 0) ?>">
                                                                <?= htmlspecialchars($course['course_name'] ?? '') ?>
                                                        </option>
                                                <?php endforeach; ?>
                                        </select>
                </div>

                <div class="modal-footer">
                    <button class="btn pink-btn" onclick="addCourse()">Add</button>
                </div>

            </div>
        </div>
    </div>

</body>

</html>