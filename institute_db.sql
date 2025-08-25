-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2025 at 07:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `institute_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `full_name`) VALUES
(1, 'admin', '$2y$10$25.oE07gBV47TDYXQXedi.uda6miH3HK4n9JflmK93Eg48Pdt8cXW', 'admin@skillpro.lk', 'Admin User');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `student_id`, `course_id`, `certificate_number`, `issue_date`, `instructor_id`, `created_at`) VALUES
(5, 1, 10, 'SPC-2025-8083', '2025-08-22', 3, '2025-08-21 15:32:23'),
(6, 4, 8, 'SPC-2025-7302', '2025-08-12', 4, '2025-08-25 16:12:51');

-- --------------------------------------------------------

--
-- Table structure for table `class_schedules`
--

CREATE TABLE `class_schedules` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `class_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_schedules`
--

INSERT INTO `class_schedules` (`id`, `course_id`, `instructor_id`, `class_date`, `start_time`, `end_time`, `topic`, `location`, `created_at`) VALUES
(1, 4, 1, '2025-08-26', '09:30:00', '13:30:00', 'Class', '6th flow', '2025-08-25 17:01:43'),
(2, 7, 2, '2025-08-27', '22:41:00', '01:38:00', 'afdae', 'aedf', '2025-08-25 17:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(3, 'Isuru Sidath', 'isuru@gmail.com', 'There are some issues with my course payments. I need to know about them.', '2025-08-21 15:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `duration` varchar(50) NOT NULL,
  `fees` decimal(10,2) NOT NULL,
  `payment_options` text NOT NULL,
  `mode` enum('Online','On-site','Hybrid') NOT NULL,
  `branch` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `category`, `description`, `duration`, `fees`, `payment_options`, `mode`, `branch`, `image`, `instructor_id`, `start_date`, `created_at`) VALUES
(4, 'Software Engineer', 'ICT', 'The Software Engineering course equips students with the knowledge and practical skills to design, develop, and manage high-quality software systems. Covering programming, databases, web & mobile apps, cloud computing, and emerging technologies, the course emphasizes hands-on learning, problem-solving, and industry projects. Graduates are prepared for careers as Software Engineers, Developers, Analysts, and IT Professionals in a rapidly evolving tech industry.', '1 Year', 650000.00, '', 'On-site', 'Colombo', '68a73213ec41e.jpg', 1, '2025-07-16', '2025-08-13 13:36:08'),
(7, 'Automotive Engineering', 'Automotive', 'The Automotive Engineering course equips students with knowledge and practical skills in vehicle design, engine systems, automotive electronics, and emerging technologies such as hybrid and electric vehicles. With hands-on training and industry projects, graduates are prepared for careers as Automotive Engineers, R&D Specialists, and Vehicle Design Professionals in the global automotive industry.', '3 Year', 2500000.00, '', 'On-site', 'Colombo', '68a732f338424.jpg', 2, '2025-07-09', '2025-08-21 14:53:39'),
(8, 'Data Engineering', 'ICT', 'The Data Engineering course equips students with skills to design, build, and manage large-scale data systems, including databases, data warehouses, ETL processes, and big data technologies. Graduates are prepared for careers as Data Engineers, BI Developers, and Big Data Specialists in data-driven industries.', '2 Year', 3000000.00, '', 'Hybrid', 'All', '68a73398a9cb8.jpg', 5, '2025-10-01', '2025-08-21 14:56:24'),
(9, 'Electrical Engineer', 'Electrical', 'The Electrical Engineering course provides students with knowledge and practical skills in power systems, electronics, control systems, and renewable energy technologies. Graduates are prepared for careers as Electrical Engineers, Power System Analysts, Automation Specialists, and Renewable Energy Professionals.', '1 Year', 1200000.00, '', 'On-site', 'Kandy', '68a7347b640b3.jpg', 6, '2025-09-06', '2025-08-21 15:00:11'),
(10, 'Hotel Management', 'Hotel Management', 'The Hotel Management course equips students with skills in hospitality operations, front office management, food & beverage services, and tourism management. Graduates are prepared for careers as Hotel Managers, Front Office Supervisors, Event Coordinators, and Hospitality Professionals.', '6 month', 220000.00, '', 'Online', 'All', '68a7368830880.jpg', 3, '2025-07-31', '2025-08-21 15:08:56');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `payment_method` enum('Online','Bank Transfer','Cash') NOT NULL,
  `payment_status` enum('Pending','Completed') DEFAULT 'Pending',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `branch`, `payment_method`, `payment_status`, `enrolled_at`) VALUES
(5, 1, 10, 'Colombo', 'Online', 'Pending', '2025-08-21 15:30:14'),
(6, 1, 4, 'Colombo', 'Online', 'Pending', '2025-08-25 15:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `branch` varchar(50) NOT NULL,
  `event_type` enum('Workshop','Seminar','Job Fair','Batch Start','Holiday') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `location`, `poster`, `branch`, `event_type`, `created_at`) VALUES
(6, '2025 IELTS Batch', 'Kickstart your IELTS preparation with our new batch starting on 1st July 2022! Join our comprehensive IELTS course designed to help you achieve your target band with expert guidance from experienced instructors. The program covers all four modules—Listening, Reading, Writing, and Speaking—with practice tests, personalized feedback, and proven strategies to boost your performance. Don’t miss this opportunity to improve your skills and secure your success. Limited seats available – enroll now!', '2025-10-15', '10:00:00', '12:00:00', 'Auditorium', '68a73869ccb12.jpg', 'Colombo', 'Batch Start', '2025-08-21 15:16:57'),
(7, 'Job Fair', 'Join us at the Job Fair 2025 and explore exciting career opportunities across multiple industries! Meet top employers and recruiters, get your resume reviewed, and receive expert guidance on interviews and career growth. This is your chance to network, learn, and take the next step in your professional journey. Don’t miss out – mark your calendar and be ready to unlock your future!', '2025-11-25', '09:00:00', '15:00:00', 'Auditorium', '68a738d6b2507.jpg', 'All', 'Job Fair', '2025-08-21 15:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `qualifications` text NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `qualifications`, `specialization`, `bio`, `profile_image`, `experience`, `branch`, `status`, `created_at`) VALUES
(1, 'Sadun', '$2y$10$Ryzmk8k1cLX7FGNUkkeqNeQqj2gJsUBl2L9CgujYDJjEJy7oKybbW', 'Sadun Liyanage', 'sadun@gmail.com', '0712659845', 'Bachelor of Science (B.Sc.) in Software Engineering\r\n\r\nMaster of Science (M.Sc.) in Information Technology / Software Engineering\r\n\r\nProfessional Certifications: Oracle Certified Java Programmer (OCJP), AWS Certified Solutions Architect, Microsoft Certified Professional (MCP)', 'ICT', 'Mr. Sadun is a passionate Software Engineering lecturer with both academic and industry expertise. With a strong foundation in modern programming, cloud technologies, and system design, he has guided students to develop innovative and practical solutions that meet real-world needs.\r\n\r\nHe is deeply committed to shaping the next generation of IT professionals, ensuring students gain hands-on knowledge in areas such as web engineering, mobile app development, and emerging technologies like AI and cloud computing.\r\n\r\nMr. Sadun has also contributed to research and professional projects in software architecture and intelligent systems, and continues to mentor aspiring engineers to excel in both academia and the IT industry.', '68a72a41c9d33.jpg', '3-5 years', 'Colombo', 'Active', '2025-08-12 17:06:34'),
(2, 'Janith', '$2y$10$lNZmfnl.r/GrDmqfSju4ZuDyXtvoBova/.5.jBniCNQcOzYbww0R.', 'Janith Perera', 'janith@gmail.com', '0123654896', 'Bachelor of Science (B.Sc.) in Automotive Engineering\r\n\r\nMaster of Science (M.Sc.) in Automotive Engineering\r\n\r\nProfessional Certifications: Automotive Service Excellence (ASE), Certified SolidWorks Professional (CSWP), Hybrid & Electric Vehicle Technology Training', 'Automotive', 'Mr. Janith is a skilled academic and professional in the field of Automotive Engineering, with expertise in vehicle design, engine technology, hybrid & electric systems, and automotive diagnostics. With hands-on industry experience, he bridges the gap between theoretical knowledge and real-world automotive applications.\r\n\r\nHe has contributed to research and development projects focusing on sustainable mobility, advanced vehicle systems, and modern manufacturing technologies. As a lecturer, Mr. Janith is dedicated to inspiring students to become innovative engineers, equipping them with both technical knowledge and practical skills needed in today’s fast-evolving automotive industry.', '68a72a6c9af3c.jpg', '1-3 years', 'Matara', 'Active', '2025-08-12 17:07:21'),
(3, 'Pubudu', '$2y$10$tJwGNrtbawvIJi30Rn1IuujL18RnKetjw9TakLQkAe/kWloTxsCGu', 'Pubudu Weerasinha', 'pubudu@gmail.com', '0714576958', 'Bachelor of Science (B.Sc.) in Hospitality & Tourism Management\r\n\r\nMaster of Science (M.Sc.) in Hospitality Management\r\n\r\nProfessional Certifications: Certified Hospitality Educator (CHE), Food Safety & Hygiene Certification, Customer Service & Leadership Training', 'Hotel Management', 'Mr. Pubudu is a dedicated lecturer in Hotel and Hospitality Management, bringing both academic expertise and rich industry experience to the classroom. With a strong background in hotel operations, food & beverage management, front office management, and tourism studies, he has worked closely with the hospitality industry to train future professionals.\r\n\r\nHe has a passion for developing students’ practical skills alongside theoretical knowledge, ensuring they are well-prepared for the fast-paced global hospitality sector. Mr. Pubudu has also contributed to training programs, workshops, and industry collaborations, helping students build the confidence and professionalism required for successful careers in hotel and tourism management.', '68a72fd37c00a.jpg', '10+ years', 'All', 'Active', '2025-08-17 05:30:29'),
(4, 'Nipuni', '$2y$10$KN/UOPOmqgjKIN4sxqUYRezbUeBGFyrUHFQLJ2obMrJI7pqjC60xW', 'Nipuni Kavindi', 'nipuni@gmail.com', '0726598555', 'Bachelor of Science (B.Sc.) in Culinary Arts\r\n\r\nMaster’s Degree in Culinary Innovation\r\n\r\nProfessional Certifications: Diploma in Professional Cookery, Food Safety & Hygiene Certification, Pastry & Bakery Arts, International Culinary Training', 'Culinary Arts', 'Ms. Nipuni is a passionate Culinary Arts lecturer with expertise in professional cookery, pastry & bakery arts, gastronomy, and food presentation techniques. With hands-on industry experience in hospitality and fine dining, she brings practical knowledge and creativity into her teaching.\r\n\r\nShe is dedicated to mentoring students to master culinary techniques, kitchen management, and modern food trends, ensuring they gain the skills needed to excel in the competitive hospitality and culinary industry.\r\n\r\nBeyond teaching, Ms. Nipuni has contributed to culinary workshops, food innovation projects, and professional training programs, inspiring young chefs to combine tradition with creativity in their culinary journey.', '68a72a316bf73.jpg', '3-5 years', 'Kandy', 'Active', '2025-08-17 05:31:45'),
(5, 'Manoj', '$2y$10$1SqSkSkYGacSmawILpiXceH6SvSFuaga0Nq1vWg3TBxqRwooR0PxW', 'Manoj Silva', 'manoj@gmail.com', '0714578999', 'Bachelor of Science (B.Sc.) in Software Engineering\r\n\r\nMaster of Science (M.Sc.) in Software Engineering\r\n\r\nProfessional Certifications: Oracle Certified Professional (OCP), Microsoft Certified Solutions Developer (MCSD), AWS Certified Solutions Architect, etc.\r\n\r\nOngoing research/PhD (if applicable) in Artificial Intelligence, Data Science, or Software Architecture', 'ICT', 'Mr.Manoj is a dedicated academic and industry professional with extensive experience in software development, IT consultancy, and teaching. With a strong background in modern programming languages, database systems, and cloud technologies, he has guided numerous students to build innovative software solutions.\r\n\r\nHe has a passion for bridging the gap between theory and real-world application, ensuring students gain practical exposure alongside academic knowledge. Manoj has contributed to research and projects in areas such as artificial intelligence, web engineering, and enterprise systems, and continues to mentor students in developing industry-ready skills.', '68a72ce410f3b.jpg', '1-3 years', 'All', 'Active', '2025-08-21 14:27:48'),
(6, 'Kamal', '$2y$10$RGTTDtrxgOWyemFaSZL0we9cwuVpKrJeyAQR4cIVj/seSw.ELPP1q', 'Kamal Amarasuriya', 'kamal@gmail.com', '0714576958', 'Bachelor of Science (B.Sc.) in Electrical Engineering\r\n\r\nMaster of Science (M.Sc.) in Electrical Engineering\r\n\r\nProfessional Certifications: Certified Electrical Engineer (CEng), Project Management Professional (PMP), Renewable Energy & Power Systems Training', 'Electrical', 'Mr. Kamal is an experienced Electrical Engineering lecturer with expertise in power systems, electronics, renewable energy, and automation technologies. With a strong academic background and practical industry knowledge, he has contributed to projects involving electrical design, control systems, and sustainable energy solutions.\r\n\r\nPassionate about teaching, he is committed to equipping students with both theoretical understanding and hands-on technical skills, preparing them for real-world engineering challenges. Mr. Kamal has also been actively involved in research, consultancy, and training workshops, focusing on advancing modern electrical engineering practices and innovations.', '68a730b4240f4.jpeg', '5-10 years', 'Colombo', 'Active', '2025-08-21 14:44:04'),
(7, 'Domani', '$2y$10$5VAy8mNVoQ3OfiUIo7oFCesSyfJ9zsMdedM26vfw.1HoxwHoVckHu', 'Domani Perera', 'domani@gmail.com', '0745445899', 'Diploma / Bachelor’s Degree in Mechanical Engineering\r\n\r\nAdvanced Certification in Plumbing Design & Technology\r\n\r\nProfessional Certifications: Certified Plumbing Engineer (CPE), Water Supply & Sanitation Training, Green Building & Sustainable Plumbing Systems', 'Plumbing', 'Mr. Domani is a dedicated Plumbing Engineering lecturer with both academic knowledge and practical industry experience in water supply systems, sanitary engineering, building services, and sustainable plumbing solutions. He has been actively involved in designing, installing, and maintaining plumbing systems in residential, commercial, and industrial projects.\r\n\r\nWith a strong passion for teaching, Mr. Domani focuses on equipping students with hands-on technical skills, safety standards, and modern plumbing practices that meet global industry requirements. He has also contributed to training programs, workshops, and consultancy projects, inspiring students to excel as skilled professionals in plumbing and building services engineering.', '68ac8956a8cc7.jpg', '3-5 years', 'All', 'Active', '2025-08-25 15:49:30');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `username`, `password`, `email`, `full_name`, `phone`, `profile_image`) VALUES
(1, 'isuru', '$2y$10$te7xkaZpRj9lNQlkrDJf5erK/X5dqDcW2X53g8RnNV3pRrnUk1Use', 'isuru@gmail.com', 'Isuru Sidath', '0123654896', 'assets/images/profiles/profile_1_1755406194.jpg'),
(4, 'sithira', '$2y$10$xC8W5wHfwpnux0HykFg5U.wBpTZKG3Zsna/J1Lv/okZptYLmGAoOG', 'sithira@gmail.com', 'Sithira Lakvindu', '0712659874', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_courses_instructors` (`instructor_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_enrollments_students` (`student_id`),
  ADD KEY `fk_enrollments_courses` (`course_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class_schedules`
--
ALTER TABLE `class_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `certificates_ibfk_3` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`);

--
-- Constraints for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD CONSTRAINT `class_schedules_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_schedules_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_instructors` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enrollments_courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `fk_enrollments_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
