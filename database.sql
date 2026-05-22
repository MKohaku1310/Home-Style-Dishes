CREATE DATABASE IF NOT EXISTS `ql_phong_hoc` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ql_phong_hoc`;

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table structure for table `roles`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_name` VARCHAR(100) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `roles`
INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'lecturer'),
(3, 'student');

-- --------------------------------------------------------
-- Table structure for table `rooms`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_number` VARCHAR(50) UNIQUE NOT NULL,
  `floor` INT DEFAULT NULL,
  `capacity` INT DEFAULT NULL,
  `room_type` VARCHAR(50) DEFAULT 'standard',
  `status` VARCHAR(50) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `rooms`
INSERT INTO `rooms` (`id`, `room_number`, `floor`, `capacity`, `room_type`, `status`) VALUES
(1, 'P101', 3, 40, 'standard', 'maintenance'),
(2, 'P102', 2, 64, 'lab_pc', 'available'),
(3, 'P201', 2, 80, 'standard', 'maintenance'),
(4, 'P305', 3, 40, 'lab_server', 'available'),
(5, 'P301', 3, 30, 'lab_pc', 'maintenance'),
(6, 'P304', 3, 40, 'standard', 'available'),
(7, 'P103', 3, 45, 'standard', 'available'),
(8, 'P202', 2, 50, 'lab_pc', 'available'),
(9, 'P302', 3, 60, 'standard', 'available'),
(10, 'P401', 4, 40, 'standard', 'available'),
(11, 'P402', 4, 45, 'lab_pc', 'available'),
(12, 'P501', 5, 40, 'standard', 'available'),
(13, 'P502', 5, 55, 'lab_server', 'available'),
(15, 'P602', 6, 40, 'lab_pc', 'available');

-- --------------------------------------------------------
-- Table structure for table `room_sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `room_sessions`;
CREATE TABLE IF NOT EXISTS `room_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_name` VARCHAR(100) DEFAULT NULL,
  `start_time` VARCHAR(20) DEFAULT NULL,
  `end_time` VARCHAR(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `room_sessions`
INSERT INTO `room_sessions` (`id`, `session_name`, `start_time`, `end_time`) VALUES
(1, 'Ca 1', '07:00', '09:25'),
(2, 'Ca 2', '09:35', '12:00'),
(3, 'Ca 3', '12:30', '14:55'),
(4, 'Ca 4', '15:05', '17:30'),
(5, 'Ca 5', '18:00', '20:25'),
(6, 'Ca 6', '20:30', '22:00');

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(150) UNIQUE NOT NULL,
  `role_id` INT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `email`, `role_id`, `status`, `created_at`) VALUES
(1, 'admin', '$2y$10$VhS3aBCbNraZs/mAwl7PS.cOTc8CAWCuWcrL5dplymj40cy9zta4K', 'Quản trị viên', 'admin@ptit.edu.vn', 1, 'active', '2026-04-16 07:08:40'),
(2, 'testuser123', '$2y$10$SYz8SfgjDdETwMuhsz/1ue6FU3V3g/PGmPDCwyEVvNEQ3NBnwm3Ha', 'Test User', 'testuser123@student.ptit.edu.vn', 3, 'active', '2026-04-16 16:50:32'),
(3, 'testverify', '$2y$10$5D/.JfOSSksQjWO24YqNsecg6G82M1dOO1Ua8eV1K0uk0s2XgRb4q', 'Test Verify', 'test@ptit.edu.vn', 3, 'active', '2026-04-16 17:09:05'),
(5, 'user_test', '$2y$10$rUKYSoc8D5q2Whtqsr/eDO5Daf/8pa3vqaKBWYkNETSt1XweazuQW', 'Người Dùng Thử Nghiệm', 'user@test.com', 3, 'active', '2026-04-23 09:17:42'),
(6, 'test', '$2y$10$C288flyQOip3n4Lw1VS3RedP7z09RpXcQ8KUWzVa2CoFS4YDBdO9O', 'test', 'hehe@stu', 3, 'active', '2026-04-23 09:20:55');

-- --------------------------------------------------------
-- Table structure for table `equipment`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `equipment`;
CREATE TABLE IF NOT EXISTS `equipment` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_id` INT DEFAULT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `brand` VARCHAR(255) DEFAULT NULL,
  `quantity` INT DEFAULT 1,
  `condition_status` VARCHAR(50) DEFAULT 'good',
  `equipment_code` VARCHAR(100) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `purchase_date` DATE DEFAULT NULL,
  FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `equipment`
INSERT INTO `equipment` (`id`, `room_id`, `name`, `brand`, `quantity`, `condition_status`, `equipment_code`, `category`, `purchase_date`) VALUES
(3, NULL, '', NULL, 1, 'good', '', 'network', '2026-04-22'),
(4, NULL, '', NULL, 1, 'good', '', 'network', '2026-04-22'),
(5, NULL, '', NULL, 1, 'good', '', 'network', '2026-04-22'),
(6, 1, 'hjkh', NULL, 1, 'good', 'h', 'pc', '2026-04-22'),
(7, 1, 'PC', NULL, 1, 'repairing', '12', 'monitor', '2026-04-23');

-- --------------------------------------------------------
-- Table structure for table `bookings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `room_id` INT DEFAULT NULL,
  `session_id` INT DEFAULT NULL,
  `booking_date` DATE DEFAULT NULL,
  `purpose` TEXT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'pending',
  `admin_note` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`session_id`) REFERENCES `room_sessions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `bookings`
INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `session_id`, `booking_date`, `purpose`, `status`, `admin_note`, `created_at`) VALUES
(1, 1, 5, 1, '2026-04-16', 'Oke oke ', 'approved', NULL, '2026-04-16 16:32:32'),
(2, 1, 1, 1, '2026-04-16', 'Study group PTIT', 'approved', NULL, '2026-04-16 16:46:28'),
(3, 1, 1, 1, '2026-04-16', 'Testing system functionality for software development class', 'rejected', 'Không đủ điều kiện hoặc trùng lịch', '2026-04-16 16:55:11'),
(4, 1, 1, 1, '2026-04-16', 'Conflict check test', 'approved', NULL, '2026-04-16 16:56:45'),
(5, 3, 1, 1, '2026-04-17', 'Test booking P101 for tomorrow Ca 1.', 'approved', NULL, '2026-04-16 17:14:41'),
(6, 1, 1, 2, '2026-04-17', 'Mượn', 'approved', NULL, '2026-04-17 00:56:20'),
(7, 1, 3, 2, '2026-04-17', 'Mượn p2', 'rejected', 'Không đủ điều kiện hoặc trùng lịch', '2026-04-17 00:57:05'),
(8, 1, 6, 5, '2026-04-17', 'Mươn p3', 'approved', NULL, '2026-04-17 01:33:08'),
(9, 1, 2, 3, '2026-04-17', 'hI', 'approved', NULL, '2026-04-17 01:37:23'),
(10, 1, 1, 3, '2026-04-20', '.', 'rejected', 'Không đủ điều kiện hoặc trùng lịch', '2026-04-20 10:21:01'),
(11, 1, 2, 1, '2026-04-21', 'Hi', 'rejected', 'Không đủ điều kiện hoặc trùng lịch', '2026-04-21 07:21:23'),
(12, 1, 3, 1, '2026-04-21', 'Mượn ', 'approved', NULL, '2026-04-21 16:19:01'),
(13, 1, 2, 1, '2026-04-21', 'Ko', 'rejected', 'Không đủ điều kiện hoặc trùng lịch', '2026-04-21 16:21:14'),
(14, 1, 3, 3, '2026-04-22', 'Mượn', 'approved', NULL, '2026-04-22 01:35:18'),
(15, 1, 3, 4, '2026-04-22', 'hi', 'rejected', 'Không đủ điều kiện hoặc trùng lịch', '2026-04-22 04:28:01'),
(16, 1, 2, 1, '2026-04-22', 'Test', 'approved', NULL, '2026-04-22 07:43:56'),
(17, 1, 4, 1, '2026-04-22', 'Hi', 'approved', NULL, '2026-04-22 10:16:14'),
(18, 1, 2, 3, '2026-04-23', 'Hi', 'approved', NULL, '2026-04-23 01:50:34'),
(19, 1, 2, 2, '2026-04-23', 'Hi', 'approved', NULL, '2026-04-23 01:59:55'),
(20, 1, 6, 3, '2026-04-23', 'Hi', 'approved', NULL, '2026-04-23 02:07:15'),
(21, 1, 4, 2, '2026-04-23', 'Hi', 'approved', NULL, '2026-04-23 09:16:35'),
(22, 6, 10, 2, '2026-04-23', 'Lmao', 'approved', NULL, '2026-04-23 09:21:43'),
(23, 6, 6, 1, '2026-04-23', 'ji', 'approved', NULL, '2026-04-23 09:22:14'),
(24, 1, 8, 1, '2026-04-23', 'Hi\r\n', 'approved', NULL, '2026-04-23 16:14:37'),
(25, 1, 2, 2, '2026-04-24', 'hi\r\n', 'approved', NULL, '2026-04-24 04:26:06'),
(26, 1, 6, 1, '2026-05-21', 'hi', 'approved', NULL, '2026-05-21 07:06:11');

-- --------------------------------------------------------
-- Table structure for table `notifications`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `is_read` TINYINT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `notifications`
INSERT INTO `notifications` (`id`, `user_id`, `title`, `content`, `is_read`, `created_at`) VALUES
(1, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-16 16:33:16'),
(2, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-16 16:33:17'),
(3, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-16 16:46:48'),
(4, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-16 16:57:00'),
(5, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-16 16:57:29'),
(6, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-17 01:17:24'),
(7, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-17 01:17:25'),
(8, 3, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 0, '2026-04-17 01:17:25'),
(9, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-17 01:33:26'),
(10, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-17 02:09:47'),
(11, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-20 10:21:35'),
(12, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-21 16:19:12'),
(13, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-21 16:19:22'),
(14, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-21 16:21:29'),
(15, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-21 17:11:36'),
(16, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 01:36:18'),
(17, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 04:28:52'),
(18, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 04:28:54'),
(19, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-22 04:29:18'),
(20, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 04:29:21'),
(21, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 06:04:21'),
(22, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 06:11:18'),
(23, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 06:13:57'),
(24, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-22 07:44:53'),
(25, 1, 'Yêu cầu bị từ chối', 'Admin đã từ chối yêu cầu mượn phòng của bạn. Xem chi tiết trong lịch sử.', 1, '2026-04-22 07:44:59'),
(26, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 01:48:11'),
(27, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 02:00:09'),
(28, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 02:00:10'),
(29, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 02:07:41'),
(30, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 02:08:20'),
(31, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 04:17:47'),
(32, 6, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 0, '2026-04-23 09:22:50'),
(33, 6, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 0, '2026-04-23 09:22:51'),
(34, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 09:22:51'),
(35, 6, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 0, '2026-04-23 16:18:30'),
(36, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-04-23 16:18:30'),
(37, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-05-21 07:07:16'),
(38, 1, 'Yêu cầu được duyệt', 'Yêu cầu mượn phòng học của bạn đã được Admin phê duyệt.', 1, '2026-05-21 07:07:17');

-- --------------------------------------------------------
-- Table structure for table `reviews`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT DEFAULT NULL,
  `rating` INT CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `incident_reports`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `incident_reports`;
CREATE TABLE IF NOT EXISTS `incident_reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `room_id` INT DEFAULT NULL,
  `equipment_name` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `urgency` VARCHAR(50) DEFAULT 'medium',
  `status` VARCHAR(50) DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `incident_reports`
INSERT INTO `incident_reports` (`id`, `user_id`, `room_id`, `equipment_name`, `description`, `urgency`, `status`, `created_at`) VALUES
(1, 1, 1, 'Lmao', 'Lmu', 'medium', 'pending', '2026-04-17 01:26:51'),
(2, 1, 1, NULL, 'Hỏng máy tính', 'medium', 'pending', '2026-04-21 16:19:54'),
(3, 1, 1, NULL, 'Hỏng máy tính', 'medium', 'pending', '2026-04-21 16:19:58'),
(4, 1, 1, NULL, 'Hỏng máy tính', 'medium', 'pending', '2026-04-21 16:20:02'),
(5, 1, 1, NULL, 'Hỏng máy tính', 'medium', 'resolved', '2026-04-21 16:20:05'),
(6, 1, 2, NULL, 'Máy tính', 'medium', 'resolved', '2026-04-21 16:27:55'),
(7, 1, 2, NULL, 'Hỏng điều hòa', 'medium', 'resolved', '2026-04-21 17:10:12'),
(8, 1, 6, NULL, 'Hỏng điều hòa', 'medium', 'resolved', '2026-04-22 01:34:16'),
(9, 1, 3, NULL, 'Hỏng điều hòa', 'medium', 'resolved', '2026-04-22 07:44:18');

SET FOREIGN_KEY_CHECKS = 1;
