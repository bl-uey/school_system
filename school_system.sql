
-- Generation Time: 2026/06/21


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";





-- Database: `school_system`


-- بنية الجدول `attendance`


CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('حاضر','غائب','متأخر','بإذن') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

 --------------------------------------------------------


-- بنية الجدول `classes`


CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

 --------------------------------------------------------


-- بنية الجدول `courses`


CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-------------------------------------------------------


-- بنية الجدول `grades`


CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--------------------------------------------------------


-- بنية الجدول `students`


CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('ذكر','أنثى') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone` varchar(20) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- إرجاع أو استيراد بيانات الجدول `students`


INSERT INTO `students` (`id`, `name`, `date_of_birth`, `gender`, `address`, `phone`, `email`, `parent_name`, `parent_phone`, `enrollment_date`, `class_id`) VALUES
(0, 'hadeel', '2025-08-01', 'أنثى', 'الترنس', '0509565112', 'hadeelmalak8@gmail.com', 'احمد', '05971555', '2025-08-23', 0);

 --------------------------------------------------------


-- بنية الجدول `teachers`


CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

 --------------------------------------------------------


-- بنية الجدول `users`


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- إرجاع أو استيراد بيانات الجدول `users`


INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `created_at`) VALUES
(1, 'مدير النظام', 'admin', '12345', '2025-08-21 06:34:29');


-- Indexes for dumped tables


--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD UNIQUE KEY `email` (`email`);


-- Indexes for table `teachers`

ALTER TABLE `teachers`
  ADD UNIQUE KEY `email` (`email`);


-- Indexes for table `users`

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);
COMMIT;


