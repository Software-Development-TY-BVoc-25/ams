# Attendance Management System (AMS) – Database Documentation

This document serves as a complete guide to the **Attendance Management System (AMS)** database. It outlines the schema, design reasoning, relational flows, and constraints to make the system easy to understand and extend. Whether you're a new developer or an AI tool trying to work with the system, this will give you a full picture of how the database is structured and why.

---

## 🎯 Purpose of the System

The AMS is built to:

- Track student attendance on a subject-wise, day-wise basis
- Handle CSV imports from the admin at the start of each semester
- Allow teachers to mark attendance per subject, per month
- Maintain historical data semester-wise without overwriting old records
- Accommodate real-world needs like shared teachers across departments, non-teaching roles, and students changing enrollment over time

---

## 🔁 Key System Assumptions

- Admin uploads student data every semester, which includes semester number and academic year
- Teachers belong to one or more departments and teach one or more subjects
- A subject can exist across semesters and departments
- Students may have duplicate names but are uniquely identified by a system-assigned ID
- Attendance is marked monthly in an Excel-like format
- All past attendance records must be retained

---

## 📐 Schema Overview and Design Decisions

Each table below explains *why* it exists and *how* it connects to others.

### 1. **academic\_year** – *Tracks sessional years like '2024–25'*

```sql
CREATE TABLE academic_year (
    Year_Label VARCHAR(20) PRIMARY KEY
);
```

- No auto ID – the label itself (e.g., `'2025-26'`) is more human-readable and query-friendly
- Used in enrollment and subject allocations to distinguish across years

---

### 2. **department** – *Academic divisions like BCA, BVOC*

```sql
CREATE TABLE department (
    Department_ID INT AUTO_INCREMENT PRIMARY KEY,
    Department_Name VARCHAR(100) NOT NULL UNIQUE
);
```

- Unique names prevent duplicates
- Connected to subjects, classes, and teacher mappings

---

### 3. **class** – *Represents a batch like FY BCA A*

```sql
CREATE TABLE class (
    Class_ID INT AUTO_INCREMENT PRIMARY KEY,
    Year_Level ENUM('FY', 'SY', 'TY') NOT NULL,
    Course_Code VARCHAR(50) NOT NULL,
    Division VARCHAR(5),
    Department_ID INT NOT NULL,
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID)
);
```

- Kept separate from academic year intentionally
- Classes like `FY BCA A` stay the same each year
- Year-wise records are tracked via student enrollment instead

---

### 4. **student** – *Student personal details*

```sql
CREATE TABLE student (
    Student_ID INT AUTO_INCREMENT PRIMARY KEY,
    Student_Name VARCHAR(255) NOT NULL,
    Student_Rollno INT NOT NULL
);
```

- Stores only static identity info
- No semester/year here to avoid duplication and keep records atomic

---

### 5. **student\_semester\_enrollment** – *Links students to class/semester/year*

```sql
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
```

- Allows the same student to appear in different semesters and years
- Central to admin uploads (CSV files are parsed into this table)

---

### 6. **subject** – *All subjects grouped by semester and department*

```sql
CREATE TABLE subject (
    Subject_ID INT AUTO_INCREMENT PRIMARY KEY,
    Subject_Name VARCHAR(255) NOT NULL,
    Semester INT NOT NULL,
    Department_ID INT NOT NULL,
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID)
);
```

- Mapped directly to departments (e.g., ‘Web Dev’ in BCA)
- Reused across academic years

---

### 7. **teacher** – *Holds teacher details*

```sql
CREATE TABLE teacher (
    Teacher_ID INT AUTO_INCREMENT PRIMARY KEY,
    Teacher_Name VARCHAR(255) NOT NULL,
    Teacher_Initial VARCHAR(20),
    Teacher_Designation VARCHAR(100)
);
```

- Teachers can be reused across years and departments
- Initials are helpful for UI abbreviation (e.g., ‘AMR’ for Arvind M. Rao)

---

### 8. **teacher\_department** – *Many-to-many mapping between teachers and departments*

```sql
CREATE TABLE teacher_department (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Teacher_ID INT NOT NULL,
    Department_ID INT NOT NULL,
    FOREIGN KEY (Teacher_ID) REFERENCES teacher(Teacher_ID),
    FOREIGN KEY (Department_ID) REFERENCES department(Department_ID),
    UNIQUE (Teacher_ID, Department_ID)
);
```

- Ensures that teachers like a Math teacher can teach in both BCA and BVOC
- Used during subject allocation

---

### 9. **tr\_subject\_allocation** – *Tracks which teacher teaches which subject, to which class, in which semester and year*

```sql
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
```

- This is how the frontend knows which subject to show the teacher
- One teacher can teach multiple subjects to multiple classes

---

### 10. **attendance** – *Stores P/A/L status by student/date/subject/class*

```sql
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
```

- Matches the frontend’s sheet view (students vs dates)
- `Class_ID` allows joins with subject allocation
- `UNIQUE` constraint prevents duplicate marking

---

### 11. **user\_table** – *Holds login and access control*

```sql
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
```

- Required for all roles (admin, teacher, etc.)
- A teacher must be here to log in, but admins/principals don’t need to exist in the `teacher` table
- `Reference_ID` connects login accounts to teacher identity when needed

---

Let me know if you'd like this exported as a PDF, markdown file, or printed ER diagram.

