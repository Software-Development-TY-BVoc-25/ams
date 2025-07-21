-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 09:15 AM
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
-- Database: `attendance`
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
(2, ' FY-BCA', 'B', 1),
(3, 'SY-BCA', 'A', 1),
(4, 'SY-BCA', 'B', 1),
(5, 'TY-BCA', 'A', 1),
(6, 'TY-BCA', 'B', 1),
(7, 'FY-BVOC', NULL, 2),
(8, 'SY-BVOC', NULL, 2),
(9, 'TY-BVOC', NULL, 2);

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
(9, 'data structures', 2, 3, 1, 1),
(10, 'Audio and visual media', 2, 3, 1, 1),
(11, 'Reasoning Techniques', 2, 3, 0, 1),
(12, 'Software Laboratory III', 2, 3, 1, 0),
(13, 'Associate-Desktop Publishing', 2, 3, 1, 0),
(14, 'python programming', 2, 4, 1, 1),
(15, 'software engineering and testing', 2, 4, 1, 1),
(16, 'creative thinking', 2, 4, 0, 1),
(17, 'software laboratory-IV', 2, 4, 1, 0),
(19, 'Mobile Application Development', 2, 5, 1, 1),
(20, 'Human Computer Interaction', 2, 5, 0, 1),
(21, 'Advance Quantitative Techniques', 2, 5, 0, 1),
(22, 'Software Laboratory V', 2, 5, 1, 0),
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

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`Teacher_ID`, `User_ID`, `Teacher_Name`, `Teacher_Initial`) VALUES
(1, 3, 'Nikita Verenkar', 'NK'),
(2, 3, 'Amogh Pai Raiturkar', 'AR'),
(3, 3, 'Ramakrishna Reddy', 'RR'),
(4, 3, 'Sweta Vernekar', 'SV'),
(5, 3, 'Rama Borkar', 'RB'),
(6, 3, 'Ankita Naik', 'AN'),
(7, 3, 'Deepti Kulkarni', 'DK'),
(8, 3, 'PK', 'PK'),
(9, 3, 'Sandesh Gaundakar', 'SG'),
(11, 3, 'Annette Santimano', 'AES'),
(12, 3, 'Sameer Pundalik Patil', 'SPP'),
(13, 3, 'Sneha Prabhudessai', 'SP'),
(14, 3, 'Yugandhara Joshi', 'YJ'),
(15, 3, 'Samira Vengurlekar', 'SRV'),
(16, 3, 'Girija Gaonkar', 'GG'),
(17, 3, 'Disha Malvankar', 'DM'),
(18, 3, 'Serth Shanbhag', 'SNS'),
(19, 3, 'Clayton Araujo', 'CLA'),
(20, 3, 'Mayuri Haldankar', 'MH'),
(21, 3, 'Varsha Prabhu gaonkar', 'VPG'),
(22, 3, 'Vaibhav Majalikar', 'VM'),
(23, 3, 'Ciana Fernandes', 'CF'),
(24, 3, 'Andre Pacheco', 'AP'),
(25, 3, 'Sumit Kumar', 'SK');

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

--
-- Dumping data for table `tr_subject_allocation`
--

INSERT INTO `tr_subject_allocation` (`Allocation_ID`, `Teacher_ID`, `Subject_ID`, `Class_ID`, `Department_ID`) VALUES
(1, 2, 1, 7, 2),
(2, 3, 2, 7, 2),
(3, 1, 3, 7, 2),
(4, 5, 4, 7, 2),
(5, 6, 9, 8, 2),
(6, 7, 10, 8, 2),
(7, 8, 11, 8, 2),
(8, 11, 12, 8, 2),
(9, 7, 12, 8, 2),
(10, 9, 13, 8, 2),
(11, 12, 19, 9, 2),
(12, 13, 20, 9, 2),
(13, 14, 21, 9, 2),
(14, 12, 22, 9, 2),
(15, 11, 23, 9, 2),
(16, 15, 23, 9, 2);

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
