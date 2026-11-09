-- =======================================================
-- XML Student Database Schema for XAMPP MySQL
-- =======================================================

CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rollNo VARCHAR(50),
    name VARCHAR(100) NOT NULL,
    age INT,
    dob DATE,
    gender VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    course VARCHAR(100),
    skills VARCHAR(255),
    city VARCHAR(100),
    batch_time VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert starter records matching students.xml
INSERT IGNORE INTO students 
(id, rollNo, name, age, dob, gender, email, phone, course, skills, city, batch_time, address) 
VALUES 
(1, 'CSB-2026', 'Alex Johnson', 21, '2005-04-12', 'Male', 'alex@example.com', '9876543210', 'BSc CS', 'HTML, CSS, JavaScript, PHP', 'Chennai', '09:00', '124 Tech Park Road, Velachery, Chennai'),
(2, 'MCA-2026', 'Sophia Miller', 23, '2003-08-25', 'Female', 'sophia@example.com', '9123456780', 'MCA', 'Python, MySQL, PHP', 'Bangalore', '14:00', '78 Silicon Valley Residency, Indiranagar, Bangalore');
