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

        $name = trim($_POST['name'] ?? '');
        $hours = trim($_POST['hours'] ?? '');

        if ($name === '' || $hours === '') {
            \App\Alert::printMessage('All fields are required', 'danger');
            return;
        }

        if (!ctype_digit($hours) || (int)$hours <= 0) {
            \App\Alert::printMessage('Hours must be a valid positive integer', 'danger');
            return;
        }
        $hours = (int)$hours;

        $stmt = $this->db->connection->prepare('INSERT INTO courses (course_name, hours) VALUES (?, ?)');
        $stmt->bind_param('si', $name, $hours);
        if ($stmt->execute()) {
            \App\Alert::printMessage('Course added successfully', 'success');
        } else {
            \App\Alert::printMessage('Failed to add course: ' . $stmt->error, 'danger');
        }
    }
    
    public function updateCourse(): void
    {
      
    }
    
    public function deleteCourse(): void
    {
        
    }
}