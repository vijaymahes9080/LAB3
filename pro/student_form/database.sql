-- =======================================================
-- Student Management System Database Schema
-- Database: student_db
-- =======================================================

CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

-- Drop table if exists for clean reset (optional)
-- DROP TABLE IF EXISTS students;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_id VARCHAR(50) UNIQUE,
    name VARCHAR(100) NOT NULL,
    age INT,
    dob DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    portfolio VARCHAR(255) DEFAULT NULL,
    course VARCHAR(100) NOT NULL,
    electives VARCHAR(255) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    batch_time TIME DEFAULT NULL,
    interview_slot DATETIME DEFAULT NULL,
    grad_month VARCHAR(20) DEFAULT NULL,
    orientation_week VARCHAR(20) DEFAULT NULL,
    skills VARCHAR(255) DEFAULT NULL,
    experience INT DEFAULT 50,
    badge_color VARCHAR(10) DEFAULT '#007bff',
    interests VARCHAR(255) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional Sample Starter Data
INSERT IGNORE INTO students 
(app_id, name, age, dob, gender, email, password, phone, portfolio, course, electives, city, batch_time, skills, experience, badge_color, interests, address) 
VALUES 
('APP-2026-101', 'Alex Johnson', 21, '2005-04-12', 'Male', 'alex@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEF', '9876543210', 'https://alexdev.io', 'BSc CS', 'Cloud Computing, AI & ML', 'Chennai', '09:00:00', 'HTML, CSS, JavaScript, PHP', 75, '#2e7d32', 'Full Stack Development, AI', '124 Tech Park Road, Velachery, Chennai'),
('APP-2026-102', 'Sophia Miller', 23, '2003-08-25', 'Female', 'sophia@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEF', '9123456780', 'https://sophiadesign.com', 'MCA', 'Data Science, Cyber Security', 'Bangalore', '14:00:00', 'Python, MySQL, PHP', 85, '#6a1b9a', 'Cybersecurity, Cloud Arch', '78 Silicon Valley Residency, Indiranagar, Bangalore');
