-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2025 at 10:19 AM
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
  `Attendance_ID` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Department_ID` int(11) NOT NULL,
  `Department_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Practical` tinyint(1) DEFAULT NULL,
  `theory` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`Attendance_ID`),
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
