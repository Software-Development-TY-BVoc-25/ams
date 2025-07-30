-- -----------------------------
-- Academic Year Table
-- -----------------------------
CREATE TABLE academic_year (
    Year_Label VARCHAR(20) PRIMARY KEY
);

-- -----------------------------
-- Department Table
-- -----------------------------
CREATE TABLE department (
    Department_ID INT AUTO_INCREMENT PRIMARY KEY,
    Department_Name VARCHAR(100) NOT NULL UNIQUE
);

-- -----------------------------
-- Class Table
-- -----------------------------
CREATE TABLE class (
    Class_ID INT AUTO_INCREMENT PRIMARY KEY,
    Year_Level ENUM('FY', 'SY', 'TY') NOT NULL,
    Course_Code VARCHAR(20) NOT NULL,
    Division VARCHAR(5),
    Department_ID INT NOT NULL,
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID)
);

-- -----------------------------
-- Student Table
-- -----------------------------
CREATE TABLE student (
    Student_Rollno INT PRIMARY KEY,
    Student_Name VARCHAR(255) NOT NULL
);

-- -----------------------------
-- Student Semester Enrollment Table
-- One student can have multiple semester enrollments (1 per semester)
-- Using Enrollment_ID as surrogate key (no composite keys)
-- -----------------------------
CREATE TABLE student_enrollment (
    Enrollment_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_Rollno INT NOT NULL,
    Class_ID INT NOT NULL,
    Semester INT NOT NULL,
    Year_Label VARCHAR(20) NOT NULL,
    FOREIGN KEY (Student_Rollno) REFERENCES student(Student_Rollno),
    FOREIGN KEY (Class_ID) REFERENCES class(Class_ID),
    FOREIGN KEY (Year_Label) REFERENCES academic_year(Year_Label),
    UNIQUE (Student_Rollno, Class_ID, Semester, Year_Label),
    INDEX idx_rollno (Student_Rollno)
);

-- -----------------------------
-- Subject Table
-- -----------------------------
CREATE TABLE subject (
    Subject_ID INT AUTO_INCREMENT PRIMARY KEY,
    Subject_Name VARCHAR(255) NOT NULL,
    Semester INT NOT NULL,
    Department_ID INT NOT NULL,
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID)
);

-- -----------------------------
-- Teacher Table
-- -----------------------------
CREATE TABLE teacher (
    Teacher_ID INT AUTO_INCREMENT PRIMARY KEY,
    Teacher_Name VARCHAR(255) NOT NULL,
    Teacher_Initial VARCHAR(20),
    Teacher_Designation VARCHAR(100)
);

-- -----------------------------
-- Teacher-Department Mapping Table
-- -----------------------------
CREATE TABLE teacher_department (
    Teacher_ID INT NOT NULL,
    Department_ID INT NOT NULL,
    PRIMARY KEY (Teacher_ID, Department_ID),
    FOREIGN KEY (Teacher_ID) REFERENCES teacher(Teacher_ID),
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID)
);

-- -----------------------------
-- Subject Allocation Table
-- Which teacher teaches what subject to which class and when
-- -----------------------------
CREATE TABLE tr_subject_allocation (
    Allocation_ID INT AUTO_INCREMENT PRIMARY KEY,
    Teacher_ID INT NOT NULL,
    Subject_ID INT NOT NULL,
    Class_ID INT NOT NULL,
    Semester INT NOT NULL,
    Year_Label VARCHAR(20) NOT NULL,
    FOREIGN KEY (Teacher_ID) REFERENCES teacher(Teacher_ID),
    FOREIGN KEY (Subject_ID) REFERENCES subject(Subject_ID),
    FOREIGN KEY (Class_ID) REFERENCES class(Class_ID),
    FOREIGN KEY (Year_Label) REFERENCES academic_year(Year_Label),
    INDEX idx_allocation_sem_year (Semester, Year_Label)
);

-- -----------------------------
-- Attendance Table
-- Linked via Enrollment_ID (not Roll No directly)
-- But easily queryable using joins with student_enrollment
-- -----------------------------
CREATE TABLE attendance (
    Attendance_ID INT AUTO_INCREMENT PRIMARY KEY,
    Enrollment_ID INT NOT NULL,
    Subject_ID INT NOT NULL,
    Attendance_Date DATE NOT NULL,
    Status ENUM('P', 'A', 'L') NOT NULL,
    FOREIGN KEY (Enrollment_ID) REFERENCES student_enrollment(Enrollment_ID),
    FOREIGN KEY (Subject_ID) REFERENCES subject(Subject_ID),
    UNIQUE (Enrollment_ID, Subject_ID, Attendance_Date),
    INDEX idx_subject_date (Subject_ID, Attendance_Date)
);

-- -----------------------------
-- User Table
-- Can be linked to teacher, admin, etc.
-- -----------------------------
CREATE TABLE user_table (
    User_ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    User_Type VARCHAR(50) NOT NULL,
    IsActive BOOLEAN DEFAULT TRUE,
    Reference_ID INT,
    FOREIGN KEY (Reference_ID) REFERENCES teacher(Teacher_ID)
);


-- -----------------------------
-- FINALIZED INSERT DATA SCRIPT (AUTO_INCREMENT FIELDS OMITTED + EXTRA FIELDS ADDED)
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- 1. academic_year
INSERT INTO academic_year (Year_Label) VALUES
  ('2023-24'),
  ('2024-25'),
  ('2025-26');

-- 2. department
INSERT INTO department (Department_Name) VALUES
('BCA'),
('BVOC');

-- 3. class
INSERT INTO class (Year_Level, Course_Code, Division, Department_ID) VALUES
('FY', 'BCA', 'A', 1),
('FY', 'BCA', 'B', 1),
('SY', 'BCA', 'A', 1),
('SY', 'BCA', 'B', 1),
('TY', 'BCA', 'A', 1),
('TY', 'BCA', 'B', 1),
('FY', 'BVOC', NULL, 2),
('SY', 'BVOC', NULL, 2),
('TY', 'BVOC', NULL, 2);

-- 4. student
-- Skipped: No insert data found for students.

-- 5. subject
INSERT INTO subject (Subject_Name, Semester, Department_ID) VALUES
('Computer Fundamentals and Programming Concepts', 1, 2),
('Relational Database Management System', 1, 2),
('Environmental Studies II', 1, 2),
('SSC/Q0501 Software Developer', 1, 2),
('Object-Oriented Concepts using Java', 2, 2),
('Multimedia Technologies', 2, 2),
('Quantitative Techniques', 2, 2),
('Associate-Desktop Publishing', 2, 2),
('Data Structures', 3, 2),
('Audio and Visual Media', 3, 2),
('Reasoning Techniques', 3, 2),
('Software Laboratory', 3, 2),
('Associate-Desktop Publishing', 3, 2),
('Python Programming', 4, 2),
('Software Engineering and Testing', 4, 2),
('Creative Thinking', 4, 2),
('Software Laboratory-IV', 4, 2),
('Associate Desktop Publishing', 4, 2),
('Mobile Application Development', 5, 2),
('Human Computer Interaction', 5, 2),
('Advance Quantitative Techniques', 5, 2),
('Software Laboratory', 5, 2),
('Software Developer', 5, 2),
('RDBMS', 6, 2),
('Computer Networks', 6, 2),
('Entrepreneurship Development', 6, 2),
('Software Laboratory-IV', 6, 2);

-- 6. teacher
INSERT INTO teacher (Teacher_Name, Teacher_Initial) VALUES
('Mr. Sumit Kumar', 'SK'),
('Ms. Sweta P. Shet Verenkar', 'SPV'),
('Mr. Sameer Pundalik Patil', 'SPP'),
('Mr. Ramkrishna Reddy', 'RR'),
('Mr. Andre Exequiel Anthony Pacheco', 'AP'),
('Ms. Samira Vengurlekar', 'SVR'),
('Ms. Rama Ambar Shenvi Borkar', 'RB'),
('Ms. Annette Santimano', 'AES'),
('Ms. Ankita Naik', 'AN'),
('Ms. Sneha Prabhudessai', 'SP'),
('Ms. Shruti Ashwin Kunkolienkar', 'SK'),
('Ms. Girija Vishwesh Gaonkar', 'GVG'),
('Mr. Amogh Santosh Pai Raiturkar', 'APR'),
('Ms. Deepti D Kulkarni', 'DK'),
('Ms. Vinaya Vinod Kirloskar', 'VK'),
('Ms. Yugandhara Joshi', 'YJ'),
('Ms. Namrata Prakash Ugvekar', 'NPU');

-- 7. tr_subject_allocation
-- Skipped: No insert data present

-- 8. attendance
-- Skipped: Invalid SELECT source; requires data INSERTs or valid FROM reference

-- 9. user_table
INSERT INTO user_table (Username, Password, User_Type, Email, isActive) VALUES
('Admin', 'Admin123', 'admin', 'admin@example.com', 1),
('Principal', 'Principal123', 'admin', 'principal@example.com', 1),
('Teacher', 'Teacher123', 'admin', 'teacher@example.com', 1);

SET FOREIGN_KEY_CHECKS = 1;
