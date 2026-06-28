-- ============================================================
-- HOSPITAL APPOINTMENT AND PATIENT RECORD SYSTEM
-- Full database with sample data
-- TWT6223 Web Techniques and Applications - Lab Section DS2F
-- ------------------------------------------------------------
-- HOW TO IMPORT:
--   1. Open phpMyAdmin (http://localhost/phpmyadmin)
--   2. Click the "Import" tab at the top
--   3. Choose this file (database.sql) and click "Go"
--   This creates the database, all tables, and the sample data.
--
-- NOTE: Running this will DROP (delete) any existing tables with
-- the same names first, so the data matches exactly. Only run it
-- on the project database.
-- ============================================================

CREATE DATABASE IF NOT EXISTS hospital_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE hospital_db;

-- ------------------------------------------------------------
-- Table: patients
-- ------------------------------------------------------------
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS patients;

CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100),
    age        INT,
    gender     VARCHAR(10),
    contact    VARCHAR(20),
    email      VARCHAR(100)
);

-- Sample patient data (matches the records page)
INSERT INTO patients (patient_id, full_name, age, gender, contact, email) VALUES
(1, 'Ahmad Razak', 35, 'Male',   '0123456789',  'ahmad@email.com'),
(2, 'Siti Aminah', 28, 'Female', '0198765432',  'siti@email.com'),
(4, 'John Tan',    45, 'Male',   '0171234567',  'john@email.com'),
(5, 'Nivashini',   23, 'Female', '01198765432', 'nivashini@gmail.com');

-- ------------------------------------------------------------
-- Table: appointments
-- ------------------------------------------------------------
CREATE TABLE appointments (
    appointment_id   INT AUTO_INCREMENT PRIMARY KEY,
    patient_id       INT,
    doctor_name      VARCHAR(100),
    appointment_date DATE,
    appointment_time TIME,
    status           VARCHAR(20)
);

-- Sample appointment data (matches the records page)
INSERT INTO appointments (appointment_id, patient_id, doctor_name, appointment_date, appointment_time, status) VALUES
(1, 1, 'Dr. Lim',  '2026-07-01', '09:30:00', 'Confirmed'),
(2, 2, 'Dr. Wong', '2026-07-02', '14:00:00', 'Confirmed'),
(3, 3, 'Dr. Lim',  '2026-07-03', '10:15:00', 'Completed'),
(4, 5, 'Dr. Wong', '2026-07-06', '15:00:00', 'Pending');

-- ------------------------------------------------------------
-- Table: users  (login accounts - the login module)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(100)        NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255)        NOT NULL,   -- stored hashed, never plain text
    created_at TIMESTAMP           DEFAULT CURRENT_TIMESTAMP
);
-- No sample user is inserted here because passwords must be hashed
-- by PHP. Create an account through login.php (Create Account).

