-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2021 at 05:03 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cdepaes_sysdb_21`
--

-- --------------------------------------------------------

--
-- Table structure for table `caes_courses`
--

CREATE TABLE `caes_courses` (
  `id` int(11) NOT NULL,
  `course_id` varchar(100) NOT NULL,
  `course_type` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `tuition_fee` int(11) NOT NULL,
  `downpayment` int(11) NOT NULL,
  `handout` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_courses`
--

INSERT INTO `caes_courses` (`id`, `course_id`, `course_type`, `date_from`, `date_to`, `time_from`, `time_to`, `tuition_fee`, `downpayment`, `handout`, `user_id`, `date_created`) VALUES
(1, 'COURSE_123', 6, '2021-04-01', '2021-04-30', '01:30:00', '11:30:00', 10000, 2000, 1000, 1, '2021-04-05 20:18:32'),
(2, 'COURSE_1234', 3, '2021-04-01', '2021-04-29', '01:30:00', '07:00:00', 10000, 2000, 1000, 1, '2021-04-10 15:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `caes_course_types`
--

CREATE TABLE `caes_course_types` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_course_types`
--

INSERT INTO `caes_course_types` (`id`, `type`) VALUES
(1, 'Arch Basic'),
(2, 'Arch Design'),
(3, 'Arch Rule 7 & 8'),
(4, 'Arch Refresher'),
(5, 'Interior Design Regular'),
(6, 'Interior Design Premium'),
(7, 'Interior Design Speed Drafting'),
(8, 'Interior Design Refresher'),
(9, 'Master Plumbing'),
(10, 'Landscape Arch');

-- --------------------------------------------------------

--
-- Table structure for table `caes_pages`
--

CREATE TABLE `caes_pages` (
  `page_id` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `parent` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `caes_pages`
--

INSERT INTO `caes_pages` (`page_id`, `url`, `name`, `icon`, `position`, `parent`) VALUES
(1, 'dashboard', 'Dashboard', 'fa fa-fw fa-chart-area', 1, 0),
(2, 'students', 'Students', 'fas fa-fw fa-user-graduate', 2, 0),
(3, 'courses', 'Courses', 'fas fa-fw fa-book', 3, 0),
(4, 'finance', 'Finance', 'fas fa-fw fa-coins', 4, 0),
(5, 'users', 'Users', 'fa fa-fw fa-users', 5, 0),
(6, 'admin', 'Admin', 'fas fa-fw fa-user-cog', 6, 0),
(7, '', '', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `caes_page_access`
--

CREATE TABLE `caes_page_access` (
  `pa_id` int(11) NOT NULL,
  `privilege` int(11) NOT NULL,
  `page` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `caes_page_access`
--

INSERT INTO `caes_page_access` (`pa_id`, `privilege`, `page`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `caes_schools`
--

CREATE TABLE `caes_schools` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_schools`
--

INSERT INTO `caes_schools` (`id`, `name`) VALUES
(1, 'Adamson University'),
(2, 'Ateneo De Davao University'),
(3, 'Aquinas University of Legazpi'),
(4, 'Bataan Peninsula State University - Balanga Campus'),
(5, 'Bulacan State University'),
(6, 'Cagayan State University'),
(7, 'Cebu State College of Science and Technology'),
(8, 'Central Colleges of the Philippines'),
(9, 'Dela Salle College of St. Benilde'),
(10, 'Don Bosco Technical College Cebu Institute of Technology University'),
(11, 'Far Eastern University'),
(12, 'Laguna State Polytechnic University'),
(13, 'Lyceum Northwestern University'),
(14, 'Mapua Institute of Technology'),
(15, 'Negros Oriental State University'),
(16, 'Northwestern University'),
(17, 'Nueva Ecija University of Science and Technology'),
(18, 'Polytechnic University of the Philippines'),
(19, 'Philippines School of Interior Design'),
(20, 'Saint Mary’s University'),
(21, 'Southern Iloilo Polytechnic College'),
(22, 'Technological Institute of the Philippines Manila'),
(23, 'Technological Institute of the Philippines Quezon City'),
(24, 'University of Antique'),
(25, 'University of Baguio'),
(26, 'University of the East'),
(27, 'University of Pangasinan PHINMA Education Network'),
(28, 'University of Perpetual Help System DALTA'),
(29, 'University of San Carlos'),
(30, 'University of Santo Tomas'),
(31, 'University of the Philippines Diliman'),
(32, 'University of the Philippines Mindanao'),
(33, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `caes_statuses`
--

CREATE TABLE `caes_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_statuses`
--

INSERT INTO `caes_statuses` (`id`, `status`) VALUES
(1, 'Active'),
(2, 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `caes_students`
--

CREATE TABLE `caes_students` (
  `id` int(11) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `school_id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `mobile_number` varchar(13) NOT NULL,
  `email_address` varchar(150) NOT NULL,
  `current_address` text NOT NULL,
  `status` int(11) NOT NULL,
  `photo_path` varchar(250) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_students`
--

INSERT INTO `caes_students` (`id`, `nickname`, `first_name`, `middle_name`, `last_name`, `school_id`, `birthdate`, `mobile_number`, `email_address`, `current_address`, `status`, `photo_path`, `user_id`, `date_created`) VALUES
(3, 'test2', 'test2', 'test2', 'test2', 12, '2021-04-30', '09053228055', 'jerielformento@gmail.com', 'test test test', 1, '1618142390.jpg', 1, '2021-04-05 21:20:57'),
(4, 'test', 'test', 'test', 'test', 2, '2021-04-23', '09053228055', 'sample@gmail.com', 'testtes ttest', 2, '1618142407.jpg', 1, '2021-04-05 21:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `caes_student_courses`
--

CREATE TABLE `caes_student_courses` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_enrolled` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_student_courses`
--

INSERT INTO `caes_student_courses` (`id`, `student_id`, `course_id`, `user_id`, `date_enrolled`) VALUES
(6, 3, 1, 1, '2021-04-11 18:57:07');

-- --------------------------------------------------------

--
-- Table structure for table `caes_student_notes`
--

CREATE TABLE `caes_student_notes` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_student_notes`
--

INSERT INTO `caes_student_notes` (`id`, `student_id`, `note`, `user_id`, `date_created`) VALUES
(1, 3, 'Partial payment for course 123', 1, '2021-04-11 18:59:21');

-- --------------------------------------------------------

--
-- Table structure for table `caes_student_payments`
--

CREATE TABLE `caes_student_payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_course_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `caes_student_payments`
--

INSERT INTO `caes_student_payments` (`id`, `student_id`, `student_course_id`, `details`, `amount`, `user_id`, `payment_date`) VALUES
(1, 3, 6, 'Partial payment for course 123', 2000, 1, '2021-04-11 19:00:45'),
(2, 3, 6, 'Test payment for course 123', 5000, 1, '2021-04-12 10:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `caes_users`
--

CREATE TABLE `caes_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(45) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `middle_name` varchar(45) CHARACTER SET latin1 DEFAULT '',
  `last_name` varchar(45) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `username` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `password` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `email_address` varchar(150) CHARACTER SET latin1 DEFAULT '',
  `privilege` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `caes_users`
--

INSERT INTO `caes_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `username`, `password`, `email_address`, `privilege`, `date_created`) VALUES
(1, 'Jeriel', 'Leuterio', 'Formento', 'jeriel.formento', '$2y$08$WTJ6MZrdVi0AR63REPnJCejEdetAlo6nr9qJ75PO5U7HuNncaFO7O', '', 1, '2021-03-05 15:00:00'),
(3, 'Mike', '', 'Montero', 'mike.montero', '$2y$08$7OfZ/7RZuDpcu5wVbQlkKudMartX4QM/ZH804HsL47Ttf/GRQp24C', NULL, 1, '2021-03-05 15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `caes_user_privileges`
--

CREATE TABLE `caes_user_privileges` (
  `priv_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `caes_user_privileges`
--

INSERT INTO `caes_user_privileges` (`priv_id`, `title`) VALUES
(1, 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `caes_courses`
--
ALTER TABLE `caes_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caes_course_types`
--
ALTER TABLE `caes_course_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caes_schools`
--
ALTER TABLE `caes_schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caes_statuses`
--
ALTER TABLE `caes_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caes_students`
--
ALTER TABLE `caes_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caes_student_courses`
--
ALTER TABLE `caes_student_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caes_student_courses_FK` (`student_id`),
  ADD KEY `caes_student_courses_FK_1` (`course_id`);

--
-- Indexes for table `caes_student_notes`
--
ALTER TABLE `caes_student_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caes_student_notes_FK` (`student_id`);

--
-- Indexes for table `caes_student_payments`
--
ALTER TABLE `caes_student_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caes_student_payment_FK` (`student_id`),
  ADD KEY `caes_student_payment_FK_1` (`student_course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caes_courses`
--
ALTER TABLE `caes_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `caes_course_types`
--
ALTER TABLE `caes_course_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `caes_schools`
--
ALTER TABLE `caes_schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `caes_statuses`
--
ALTER TABLE `caes_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `caes_students`
--
ALTER TABLE `caes_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `caes_student_courses`
--
ALTER TABLE `caes_student_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `caes_student_notes`
--
ALTER TABLE `caes_student_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `caes_student_payments`
--
ALTER TABLE `caes_student_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `caes_student_courses`
--
ALTER TABLE `caes_student_courses`
  ADD CONSTRAINT `caes_student_courses_FK` FOREIGN KEY (`student_id`) REFERENCES `caes_students` (`id`),
  ADD CONSTRAINT `caes_student_courses_FK_1` FOREIGN KEY (`course_id`) REFERENCES `caes_courses` (`id`);

--
-- Constraints for table `caes_student_notes`
--
ALTER TABLE `caes_student_notes`
  ADD CONSTRAINT `caes_student_notes_FK` FOREIGN KEY (`student_id`) REFERENCES `caes_students` (`id`);

--
-- Constraints for table `caes_student_payments`
--
ALTER TABLE `caes_student_payments`
  ADD CONSTRAINT `caes_student_payment_FK` FOREIGN KEY (`student_id`) REFERENCES `caes_students` (`id`),
  ADD CONSTRAINT `caes_student_payment_FK_1` FOREIGN KEY (`student_course_id`) REFERENCES `caes_student_courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
