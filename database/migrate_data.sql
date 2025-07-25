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
