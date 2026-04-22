<?php

namespace App;

use mysqli;

class DB
{
    private string $username = 'root';
    private string $password = '';
    private string $host = 'localhost';
    private string $database = 'student_db';
    public mysqli $connection;

    public function __construct()
    {
        $tempObject = new \mysqli(
            hostname: $this->host,
            username: $this->username,
            password: $this->password,
            database: $this->database,
        );
        $this->connection = $tempObject;
    }

    public function check()
    {
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
}