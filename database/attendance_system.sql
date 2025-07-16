-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 09:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `Student_ID` int(11) DEFAULT NULL,
  `Subject_ID` int(11) DEFAULT NULL,
  `Teacher_ID` int(11) DEFAULT NULL,
  `Class_ID` int(11) DEFAULT NULL,
  `Department_id` int(11) DEFAULT NULL,
  `Date_` date DEFAULT NULL,
  `Status_` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `Class_ID` int(11) NOT NULL,
  `Class_Name` varchar(255) DEFAULT NULL,
  `Division` varchar(255) DEFAULT NULL,
  `Department_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`Class_ID`, `Class_Name`, `Division`, `Department_ID`) VALUES
(1, 'FY-BCA', 'A', 1),
(2, 'FY-BCA', 'B', 1),
(3, 'SY-BCA', 'A', 1),
(4, 'SY-BCA', 'B', 1),
(5, 'TY-BCA', 'A', 1),
(6, 'TY-BCA', 'B', 1),
(7, 'FY-BVOC', NULL, 2),
(8, 'FY-BVOC', NULL, 2),
(9, 'SY-BVOC', NULL, 2),
(10, 'SY-BVOC', NULL, 2),
(11, 'TY-BVOC', NULL, 2),
(12, 'TY-BVOC', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Department_ID` int(11) NOT NULL,
  `Department_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`Department_ID`, `Department_Name`) VALUES
(1, 'BCA'),
(2, 'BVOC');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `Student_ID` int(11) NOT NULL,
  `Student_Name` varchar(255) DEFAULT NULL,
  `Student_Rollno` int(11) DEFAULT NULL,
  `Class_ID` int(11) DEFAULT NULL,
  `Department_ID` int(11) DEFAULT NULL,
  `Subject_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `Subject_ID` int(11) NOT NULL,
  `Subject_Name` varchar(255) DEFAULT NULL,
  `Department_ID` int(11) DEFAULT NULL,
  `Semester` int(11) NOT NULL,
  `Practical` tinyint(1) DEFAULT NULL,
  `theory` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`Subject_ID`, `Subject_Name`, `Department_ID`, `Semester`, `Practical`, `theory`) VALUES
(1, 'Computer Fundamentals and Programming Concepts  ', 2, 1, 1, 1),
(2, 'Relational Database Management System ', 2, 1, 1, 1),
(3, 'Environmental Studies II ', 2, 1, 1, 0),
(4, 'SSC/Q0501 Software Developer ', 2, 1, 1, 1),
(5, 'Object-Oriented Concepts using Java\r\n', 2, 2, 1, 1),
(6, 'Multimedia Technologies', 2, 2, 1, 1),
(7, 'Quantitative Techniques', 2, 2, 0, 1),
(8, 'Associate-Desktop Publishing', 2, 2, 1, 1),
(9, 'data structures', 2, 3, 1, 1),
(10, 'Audio and visual media', 2, 3, 1, 1),
(11, 'Reasoning Techniques', 2, 3, 0, 1),
(12, 'Software Laboratory', 2, 3, 1, 0),
(13, 'Associate-Desktop Publishing', 2, 3, 1, 0),
(14, 'python programming', 2, 4, 1, 1),
(15, 'software engineering and testing', 2, 4, 1, 1),
(16, 'creative thinking', 2, 4, 0, 1),
(17, 'software laboratory-IV', 2, 4, 1, 0),
(18, 'associate desktop publishing', 2, 4, 1, 1),
(19, 'Mobile Application Development', 2, 5, 1, 1),
(20, 'Human Computer Interaction', 2, 5, 0, 1),
(21, 'Advance Quantitative Techniques', 2, 5, 0, 1),
(22, 'Software Laboratory', 2, 5, 1, 0),
(23, 'Software Developer', 2, 5, 1, 1),
(24, 'RDBMS', 2, 6, 1, 0),
(25, 'computer networks', 2, 6, 0, 1),
(26, 'entrepreneurship development', 2, 6, 0, 1),
(27, 'software laboratory-IV', 2, 6, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `Teacher_ID` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Teacher_Name` varchar(225) DEFAULT NULL,
  `Teacher_Initial` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_subject_allocation`
--

CREATE TABLE `tr_subject_allocation` (
  `Allocation_ID` int(11) NOT NULL,
  `Teacher_ID` int(11) DEFAULT NULL,
  `Subject_ID` int(11) DEFAULT NULL,
  `Class_ID` int(11) DEFAULT NULL,
  `Department_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `User_ID` int(11) NOT NULL,
  `User_Name` varchar(225) DEFAULT NULL,
  `Pass` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Time_Stamp` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`User_ID`, `User_Name`, `Pass`, `Email`, `Time_Stamp`, `is_active`) VALUES
(1, 'Admin', 'Admin123', 'sejalpednekar07@gmail.com', '2025-07-08 11:41:43', 0),
(2, 'Principal', 'Principal123', 'sejalpednekar07@gmail.com', '2025-07-08 11:41:43', 0),
(3, 'Teacher', 'Teacher123', 'sejalpednekar07@gmail.com', '2025-07-08 11:41:43', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD KEY `Student_ID` (`Student_ID`),
  ADD KEY `Subject_ID` (`Subject_ID`),
  ADD KEY `Teacher_ID` (`Teacher_ID`),
  ADD KEY `Class_ID` (`Class_ID`),
  ADD KEY `Department_id` (`Department_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`Class_ID`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Department_ID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`Student_ID`),
  ADD KEY `Class_ID` (`Class_ID`),
  ADD KEY `Department_ID` (`Department_ID`),
  ADD KEY `Subject_ID` (`Subject_ID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`Subject_ID`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`Teacher_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `tr_subject_allocation`
--
ALTER TABLE `tr_subject_allocation`
  ADD PRIMARY KEY (`Allocation_ID`),
  ADD KEY `Teacher_ID` (`Teacher_ID`),
  ADD KEY `Subject_ID` (`Subject_ID`),
  ADD KEY `Class_ID` (`Class_ID`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`User_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `student` (`Student_ID`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`Subject_ID`) REFERENCES `subject` (`Subject_ID`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`Teacher_ID`) REFERENCES `teacher` (`Teacher_ID`),
  ADD CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`Class_ID`) REFERENCES `class` (`Class_ID`),
  ADD CONSTRAINT `attendance_ibfk_5` FOREIGN KEY (`Department_id`) REFERENCES `department` (`Department_ID`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`Class_ID`) REFERENCES `class` (`Class_ID`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`),
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`Subject_ID`) REFERENCES `subject` (`Subject_ID`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user_table` (`User_ID`);

--
-- Constraints for table `tr_subject_allocation`
--
ALTER TABLE `tr_subject_allocation`
  ADD CONSTRAINT `tr_subject_allocation_ibfk_1` FOREIGN KEY (`Teacher_ID`) REFERENCES `teacher` (`Teacher_ID`),
  ADD CONSTRAINT `tr_subject_allocation_ibfk_2` FOREIGN KEY (`Subject_ID`) REFERENCES `subject` (`Subject_ID`),
  ADD CONSTRAINT `tr_subject_allocation_ibfk_3` FOREIGN KEY (`Class_ID`) REFERENCES `class` (`Class_ID`),
  ADD CONSTRAINT `tr_subject_allocation_ibfk_4` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
