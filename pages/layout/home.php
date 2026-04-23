<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


$auth = new \App\Auth();
$auth->logOut();

$home = new \App\Home();
$allCourses = $home->getCourses();
$home->addCourse();
$home->deleteCourse();
$getRegeisteredCourses = $home->getUserCourses() ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="../../assets/css/home.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="card card-custom shadow p-4">

            <?php if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])): ?>
                <?php \App\Alert::printMessage($_SESSION['flash']['text'] ?? '', $_SESSION['flash']['type'] ?? 'info'); ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <h3 class="mb-3">Courses List</h3>


            <div class="text-start mt-3 mb-3">
                <button class="btn btn-sm pink-btn" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    Add Course
                </button>
            </div>

        
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

                    <?php if (empty($getRegeisteredCourses)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No registered courses yet</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($getRegeisteredCourses as $course): ?>
                            <tr>
                                <td><?= (int)($course['id'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($course['course_name'] ?? '') ?></td>
                                <td><?= (int)($course['hours'] ?? 0) ?></td>
                                <td class="text-center">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete_course">
                                        <input type="hidden" name="course_id" value="<?= (int)($course['id'] ?? 0) ?>">
                                        <button type="submit" class="btn btn-link p-0" aria-label="Remove course">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>


            <div class="text-end mt-3">
                <a href="?logout=1" class="btn btn-sm pink-btn">
                    Logout
                </a>
            </div>

        </div>

    </div>

    <?php include __DIR__ . '/partials/add_course_modal.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>