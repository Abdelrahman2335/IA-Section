CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS courses;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    registered_courses TEXT
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100),
    hours INT
);

INSERT INTO courses (course_name, hours) VALUES 
('PHP basics', 20), 
('MySQL Advanced', 30), 
('HTML5', 15), 
('CSS3', 15), 
('JavaScript', 40), 
('Laravel', 50), 
('React JS', 45), 
('Python', 35), 
('Data Science', 60), 
('Networking', 25);