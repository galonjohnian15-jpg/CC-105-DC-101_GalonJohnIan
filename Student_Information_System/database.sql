-- Active: 1766993935269@@127.0.0.1@3306@student_system

DROP DATABASE IF EXISTS student_system;
CREATE DATABASE student_system;
USE student_system;

CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(100) NOT NULL
);

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    course_id INT,
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

INSERT INTO courses (course_name) VALUES 
('Computer Science'),
('Information Technology'),
('Business Administration');

INSERT INTO students (first_name, last_name, email, course_id) VALUES
('John', 'Doe', 'john@example.com', 1),
('Jane', 'Smith', 'jane@example.com', 2);