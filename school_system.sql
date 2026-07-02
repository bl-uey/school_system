START TRANSACTION;

-- DATABASE: school_system
-- TABLE: teachers

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT '2026-06-01',
  `specialization` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- TABLE: classes

CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLE: students

CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('ذكر','أنثى') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone` varchar(20) DEFAULT NULL,
  `enrollment_date` date DEFAULT '2026-06-01',
  `class_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLE: courses
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLE: attendance

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('حاضر','غائب','متأخر','بإذن') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLE: grades
CREATE TABLE `grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `exam_date` date DEFAULT '2026-06-01',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- TABLE: users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher') DEFAULT 'teacher',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- SAMPLE DATA (ADMIN + TEACHER)

INSERT INTO `users` (`full_name`, `username`, `password`, `role`)
VALUES ('مدير النظام', 'admin', '2002', 'admin');

INSERT INTO `users` (`full_name`, `username`, `password`, `role`)
VALUES ('Teacher User', 'teacher', '2004', 'teacher');

-- FOREIGN KEYS

ALTER TABLE `classes`
  ADD CONSTRAINT `fk_classes_teacher`
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`)
  ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_teacher`
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`)
  ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_class`
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`)
  ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_student`
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grades_student`
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grades_course`
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`)
  ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

