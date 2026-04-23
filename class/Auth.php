<?php

namespace App;

class Auth implements AuthInterface
{

    private DB $db;
    public $old = [];
    public function __construct(?DB $db = null)
    {
        $this->db = $db ?? new DB();
    }

    public function signUp(): void
    {

        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {

            return;
        }

        if (($_POST['action'] ?? '') !== 'register') {
            return;
        }

        $this->old = $_POST;
        $id = trim($_POST['id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $confirmPassword = (string)($_POST['confirmPassword'] ?? '');

        if ($id === '' || $name === '' || $email === '' || $password === '' || $confirmPassword === '') {
            \App\Alert::printMessage('All fields are required', 'danger');
            return;
        }

        if (!ctype_digit($id) || (int)$id <= 0) {
            \App\Alert::printMessage('ID must be a valid integer', 'danger');
            return;
        }
        $id = (int)$id;

        if ($password !== $confirmPassword) {
            \App\Alert::printMessage('Passwords do not match', 'danger');
            return;
        }


        $checkStmt = $this->db->connection->prepare('SELECT id FROM users WHERE email = ? OR id = ? LIMIT 1');
        $checkStmt->bind_param('si', $email, $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            \App\Alert::printMessage('Email or ID already exists', 'danger');
            return;
        }

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        $userStmt = $this->db->connection->prepare('INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)');
        $userStmt->bind_param('isss', $id, $name, $email, $hashPassword);
        $userCreated = $userStmt->execute();

        if (!$userCreated) {
            \App\Alert::printMessage('Sign Up Failed', 'danger');
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['userId'] = $id;

        header('Location: home.php');
        exit;
    }

    public function logIn(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            return;
        }

        if (($_POST['action'] ?? '') !== 'login') {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            \App\Alert::printMessage('Email and password are required', 'danger');
            return;
        }



        $query = 'SELECT id, name, password FROM users WHERE email = ? LIMIT 1';
        $prepareStmt = $this->db->connection->prepare($query);
        $prepareStmt->bind_param('s', $email);
        $prepareStmt->execute();
        $resultobj = $prepareStmt->get_result();

        if ($resultobj->num_rows === 0) {
            \App\Alert::printMessage('The email you entered is incorrect', 'danger');
            return;
        }
        $rowArr = $resultobj->fetch_assoc();
        $hashedPassword = (string)($rowArr['password'] ?? '');

        if (!password_verify($password, $hashedPassword)) {
            \App\Alert::printMessage('Wrong password', 'danger');
            return;
        }

        session_regenerate_id(true);

        $_SESSION['userId'] = (int)$rowArr['id'];

        header('Location: home.php');
        exit;
    }

    public function logOut(): void
    {
        if (($_GET['logout'] ?? null) === null) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];
        session_unset();
        session_destroy();

        header('Location: login.php');
        exit;
    }
}
