
-- -----------------------------
-- Academic Year Table
-- -----------------------------
CREATE TABLE academic_year (
    Year_Label VARCHAR(20) PRIMARY KEY  -- e.g., '2025-26'
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
    Course_Code VARCHAR(50) NOT NULL,
    Division VARCHAR(5),
    Department_ID INT NOT NULL,
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID)
);

-- -----------------------------
-- Student Table
-- -----------------------------
CREATE TABLE student (
    Student_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_Name VARCHAR(255) NOT NULL,
    Student_Rollno INT NOT NULL
);

-- -----------------------------
-- Student Semester Enrollment Table
-- -----------------------------
CREATE TABLE student_semester_enrollment (
    Record_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_ID INT NOT NULL,
    Class_ID INT NOT NULL,
    Semester INT NOT NULL,
    Year_Label VARCHAR(20) NOT NULL,
    FOREIGN KEY (Student_ID) REFERENCES student(Student_ID),
    FOREIGN KEY (Class_ID) REFERENCES class(Class_ID),
    FOREIGN KEY (Year_Label) REFERENCES academic_year(Year_Label)
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
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Teacher_ID INT NOT NULL,
    Department_ID INT NOT NULL,
    FOREIGN KEY (Teacher_ID) REFERENCES teacher(Teacher_ID),
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID),
    UNIQUE (Teacher_ID, Department_ID)
);

-- -----------------------------
-- Subject Allocation Table (Teacher-Class-Subject)
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
-- -----------------------------
CREATE TABLE attendance (
    Attendance_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_ID INT NOT NULL,
    Subject_ID INT NOT NULL,
    Class_ID INT NOT NULL,
    Attendance_Date DATE NOT NULL,
    Status ENUM('P', 'A', 'L') NOT NULL,
    FOREIGN KEY (Student_ID) REFERENCES student(Student_ID),
    FOREIGN KEY (Subject_ID) REFERENCES subject(Subject_ID),
    FOREIGN KEY (Class_ID) REFERENCES class(Class_ID),
    UNIQUE (Student_ID, Subject_ID, Attendance_Date),
    INDEX idx_subject_date (Subject_ID, Attendance_Date)
);

-- -----------------------------
-- User Table
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
