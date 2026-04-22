<?php

require_once '/../vendor/autoload.php';

function signUp(): void
{
    if (isset($_POST['signUpBtn'])) {
        $id = trim($_POST['id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        if (empty($id) || empty($email) || empty($department) || empty($password) || empty($confirmPassword)) {
            \App\Alert::printMessage('All fields are required', 'danger');
            return;
        }

        if ($id <= 0) {
            \App\Alert::printMessage('ID must be a valid integer', 'danger');
            return;
        }
        $id = (int)$id;

        if ($password != $confirmPassword) {
            \App\Alert::printMessage('Passwords do not match', 'danger');
            return;
        }

        $db = new \App\DB();

        $checkStmt = $db->connection->prepare('SELECT id FROM users WHERE email = ? OR id = ?');
        $checkStmt->bind_param('si', $email, $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            \App\Alert::printMessage('Email or ID already exists', 'danger');
            return;
        }

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        $userStmt = $db->connection->prepare('INSERT INTO users (id, email, password) VALUES (?, ?, ?)');
        $userStmt->bind_param('iss', $id, $email, $hashPassword);
        $userCreated = $userStmt->execute();

        if ($userCreated) {
            $userId = $id;
            $studentStmt = $db->connection->prepare('INSERT INTO students (name, email, department, user_id) VALUES (?, ?, ?, ?)');
            $studentName = (string)$id;
            $studentStmt->bind_param('sssi', $studentName, $email, $department, $userId);
            $studentCreated = $studentStmt->execute();

            if ($studentCreated) {
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['signUpSuccess'] = 1;
                header('Location: students.php');
                exit;
            }
        }

        \App\Alert::printMessage('Sign Up Failed', 'danger');
    }
}
