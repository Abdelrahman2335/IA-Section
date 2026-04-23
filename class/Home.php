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
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
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

        $courseIdRaw = $_POST['course_id'] ?? '';
        if (!ctype_digit((string)$courseIdRaw) || (int)$courseIdRaw <= 0) {
            $_SESSION['flash'] = ['text' => 'Please select a course', 'type' => 'danger'];
            $this->redirectBack();
        }
        $courseId = (int)$courseIdRaw;

        $check = $this->db->connection->prepare('SELECT 1 FROM course_user WHERE user_id = ? AND course_id = ?');
        $check->bind_param('ii', $userId, $courseId);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;

        if ($exists) {
            $_SESSION['flash'] = ['text' => 'You already registered this course', 'type' => 'warning'];
            $this->redirectBack();
        }

        $stmt = $this->db->connection->prepare('INSERT INTO course_user (user_id, course_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $userId, $courseId);

        if ($stmt->execute()) {
            $_SESSION['flash'] = ['text' => 'Course added successfully', 'type' => 'success'];
        } else {
            $_SESSION['flash'] = ['text' => 'Failed to add course', 'type' => 'danger'];
        }

        $this->redirectBack();
    }
    
    public function updateCourse(): void
    {
      
    }
    
    public function deleteCourse(): void
    {
    }

    private function redirectBack(): void
    {
        $to = $_SERVER['PHP_SELF'] ?? '';
        if ($to === '') {
            $to = '/';
        }
        header('Location: ' . $to);
        exit;
    }
}