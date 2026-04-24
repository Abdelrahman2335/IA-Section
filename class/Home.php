<?php

namespace App;

class Home implements HomeInterface
{
    private DB $db;

    public function __construct(?DB $db = null)
    {
        $this->db = $db ?? new DB();
    }

    public function getCourses(): array
    {
        $stmt = $this->db->connection->prepare('SELECT id, course_name, hours FROM courses');
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserCourses(): ?array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = $_SESSION['userId'] ?? null;

        if (!$userId) {
            return null;
        }

        $query = 'SELECT c.id, c.course_name, c.hours
                  FROM courses c
                  JOIN course_user cu ON c.id = cu.course_id
                  WHERE cu.user_id = ?';

        $stmt = $this->db->connection->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCourse(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (($_POST['action'] ?? '') !== 'add_course') {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = (int)($_SESSION['userId'] ?? 0);

        if ($userId <= 0) {
            $_SESSION['flash'] = ['text' => 'User not logged in', 'type' => 'danger'];
            $this->redirectBack();
        }

        $courseId = (int)($_POST['course_id'] ?? 0);

        if ($courseId <= 0) {
            $_SESSION['flash'] = ['text' => 'Invalid course', 'type' => 'danger'];
            $this->redirectBack();
        }

        $checkDuplicateStmt = $this->db->connection->prepare(
            'SELECT 1 FROM course_user WHERE user_id = ? AND course_id = ?'
        );
        $checkDuplicateStmt->bind_param('ii', $userId, $courseId);
        $checkDuplicateStmt->execute();
        $duplicateResult = $checkDuplicateStmt->get_result();

        if ($duplicateResult->num_rows > 0) {
            $_SESSION['flash'] = ['text' => 'You are already registered for this course', 'type' => 'danger'];
            $this->redirectBack();
        }

        $stmt = $this->db->connection->prepare(
            'INSERT INTO course_user (user_id, course_id) VALUES (?, ?)'
        );

        $stmt->bind_param('ii', $userId, $courseId);

        if (!$stmt->execute()) {
            $_SESSION['flash'] = ['text' => 'Could not add course', 'type' => 'danger'];
            $this->redirectBack();
        }

        $_SESSION['flash'] = ['text' => 'Course added successfully', 'type' => 'success'];
        $this->redirectBack();
    }


    public function updateUserCourse(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (($_POST['action'] ?? '') !== 'update_user_course') {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = (int)($_SESSION['userId'] ?? 0);
        $oldCourseId = (int)($_POST['old_course_id'] ?? 0);
        $newCourseId = (int)($_POST['course_id'] ?? 0);

        if ($userId <= 0 || $oldCourseId <= 0 || $newCourseId <= 0) {
            $_SESSION['flash'] = ['text' => 'Invalid request', 'type' => 'danger'];
            $this->redirectBack();
        }

        if ($oldCourseId === $newCourseId) {
            $_SESSION['flash'] = ['text' => 'No changes were made', 'type' => 'info'];
            $this->redirectBack();
        }

        $checkExistingStmt = $this->db->connection->prepare(
            'SELECT 1 FROM course_user WHERE user_id = ? AND course_id = ?'
        );
        $checkExistingStmt->bind_param('ii', $userId, $oldCourseId);
        $checkExistingStmt->execute();
        $existingResult = $checkExistingStmt->get_result();

        if ($existingResult->num_rows === 0) {
            $_SESSION['flash'] = ['text' => 'Registration not found', 'type' => 'danger'];
            $this->redirectBack();
        }

        $checkDuplicateStmt = $this->db->connection->prepare(
            'SELECT 1 FROM course_user WHERE user_id = ? AND course_id = ?'
        );
        $checkDuplicateStmt->bind_param('ii', $userId, $newCourseId);
        $checkDuplicateStmt->execute();
        $duplicateResult = $checkDuplicateStmt->get_result();

        if ($duplicateResult->num_rows > 0) {
            $_SESSION['flash'] = ['text' => 'You are already registered for this course', 'type' => 'danger'];
            $this->redirectBack();
        }

        $updateStmt = $this->db->connection->prepare(
            'UPDATE course_user SET course_id = ? WHERE user_id = ? AND course_id = ?'
        );
        $updateStmt->bind_param('iii', $newCourseId, $userId, $oldCourseId);
        $updateStmt->execute();

        if ($updateStmt->affected_rows <= 0) {
            $_SESSION['flash'] = ['text' => 'Course update failed', 'type' => 'danger'];
            $this->redirectBack();
        }

        $_SESSION['flash'] = ['text' => 'Course registration updated', 'type' => 'success'];
        $this->redirectBack();
    }

    public function deleteCourse(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (($_POST['action'] ?? '') !== 'delete_course') {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = (int)($_SESSION['userId'] ?? 0);
        $courseId = (int)($_POST['course_id'] ?? 0);

        if ($userId <= 0 || $courseId <= 0) {
            $_SESSION['flash'] = ['text' => 'Invalid request', 'type' => 'danger'];
            $this->redirectBack();
        }

        $stmt = $this->db->connection->prepare(
            'DELETE FROM course_user WHERE user_id = ? AND course_id = ?'
        );

        $stmt->bind_param('ii', $userId, $courseId);
        $stmt->execute();

        $_SESSION['flash'] = ['text' => 'Course removed', 'type' => 'success'];
        $this->redirectBack();
    }

    private function redirectBack(): void
    {
        $to = $_SERVER['PHP_SELF'] ?? '/';
        header('Location: ' . $to);
        exit;
    }
}