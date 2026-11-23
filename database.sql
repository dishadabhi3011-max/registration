CREATE DATABASE IF NOT EXISTS nmc_college
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE nmc_college;

CREATE TABLE IF NOT EXISTS students (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(254) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    enrollment_number VARCHAR(50) NOT NULL,
    course VARCHAR(20) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY unique_student_email (email),
    UNIQUE KEY unique_enrollment_number (enrollment_number),
    CONSTRAINT valid_student_semester CHECK (semester BETWEEN 1 AND 8)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
