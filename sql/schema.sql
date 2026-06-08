CREATE DATABASE IF NOT EXISTS licensure_predictor
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE licensure_predictor;

DROP TABLE IF EXISTS student_grades;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(191) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  account_type ENUM('registrar', 'program chair') NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE students (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(30) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  gwa DECIMAL(5,2) NOT NULL,
  licensure_result ENUM('PASS', 'FAIL') NOT NULL DEFAULT 'FAIL',
  major_average DECIMAL(4,2) NULL,
  program VARCHAR(160) NOT NULL,
  city_municipality VARCHAR(120) NULL,
  province VARCHAR(120) NULL,
  postal_code VARCHAR(20) NULL,
  age TINYINT UNSIGNED NULL,
  sex ENUM('Male', 'Female') NULL,
  secondary_school_name VARCHAR(180) NULL,
  secondary_school_type ENUM('Private', 'Public') NULL,
  father_educational_attainment VARCHAR(80) NULL,
  mother_educational_attainment VARCHAR(80) NULL,
  monthly_family_income VARCHAR(80) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(40) NOT NULL UNIQUE,
  name VARCHAR(120) NOT NULL,
  is_major TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_courses_major (is_major),
  INDEX idx_courses_sort (sort_order)
) ENGINE=InnoDB;

CREATE TABLE student_grades (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED NOT NULL,
  grade DECIMAL(4,2) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_student_course (student_id, course_id),
  CONSTRAINT fk_student_grades_student
    FOREIGN KEY (student_id) REFERENCES students (id)
    ON DELETE CASCADE,
  CONSTRAINT fk_student_grades_course
    FOREIGN KEY (course_id) REFERENCES courses (id)
    ON DELETE CASCADE
) ENGINE=InnoDB;
