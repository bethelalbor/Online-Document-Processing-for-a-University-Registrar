-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2024 at 09:55 AM
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
-- Database: `document_request_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','superadmin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `role`) VALUES
(3, 'admin@paterostechnologicalcollege.edu.ph', '$2y$10$zx8FY.yj.ipLGoznT7rDquc21DIz9ToeThYCGnZ8XGqsp/7S9/qmq', 'admin'),
(4, 'admin@gmail.com', '$2y$10$ExOS3TDX8lSHHltKjnqGSeroosW7N/NNNKbM3jNwVT.YruqxbpCwy', 'admin'),
(6, 'superadmin@ptc.com', '$2y$10$EA2jX/JAiIGZ55lo4tOTIOsItU8.HbcIiy/4dn0tpReOSGJZXn1Cq', 'superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `type`) VALUES
(1, 'Transcript of Records'),
(2, 'Transfer Credentials (TOR, Honorable Dismissal, Certificate of Good Moral Character, Certificate of Grades, Certificate of Transfer)'),
(3, 'Diploma'),
(4, 'Certificate of Transfer'),
(5, 'Certificate of Enrollment/Registration'),
(6, 'Certificate of Grade'),
(7, 'Certificate of Honorable Dismissal'),
(8, 'Certificate of Graduation'),
(9, 'Certificate of General Weighted Average'),
(10, 'Certificate of Study Load'),
(11, 'Certification-Authentication-Verification (CAV) of Grades of TOR/Diploma (CTC)'),
(12, 'Certification of Good Moral Character'),
(13, 'Certified True Copy of Transcript of Record'),
(14, 'Certified True Copy of Diploma'),
(15, 'Certified True Copy of Certificate of Good Moral Character'),
(16, 'Certified True Copy of Certificate of Graduation'),
(17, 'Certified True Copy of Certificate of General Weighted Average'),
(18, 'Form 138 A (CTC)'),
(19, 'Form 137'),
(24, 'Test document');

-- --------------------------------------------------------

--
-- Table structure for table `history_log`
--

CREATE TABLE `history_log` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `activity` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_log`
--

INSERT INTO `history_log` (`id`, `admin_id`, `activity`, `timestamp`) VALUES
(1, 6, 'changed the request status of 28 from for scheduling to pending', '2024-06-24 03:30:34'),
(2, 6, 'changed the request status of 0616202428 from pending to for payment', '2024-06-24 05:33:21'),
(4, 6, 'changed the request status of 0616202428 from for payment to for scheduling', '2024-06-24 05:36:04'),
(5, 6, 'changed the request status of 0616202427 from for pickup to released', '2024-06-24 05:36:38'),
(6, 4, 'changed the request status of 0602202410 from for payment to for scheduling', '2024-06-24 05:38:01'),
(7, 4, 'changed the request status of 0602202418 from pending to for scheduling', '2024-06-24 05:41:40'),
(8, 6, 'logged out', '2024-06-24 06:03:05'),
(9, 4, 'logged in', '2024-06-24 06:08:09'),
(10, 4, 'logged out', '2024-06-24 06:10:14'),
(11, 6, 'logged in', '2024-06-24 06:10:20'),
(12, 6, 'logged in', '2024-06-24 06:19:04'),
(13, 6, 'logged out', '2024-06-24 06:23:27'),
(14, 3, 'logged in', '2024-06-24 06:23:35'),
(15, 3, 'logged in', '2024-06-24 06:24:09'),
(16, 3, 'logged in', '2024-06-24 06:26:18'),
(17, 3, 'changed the request status of 0624202431 from for payment to for scheduling', '2024-06-24 06:30:32'),
(18, 3, 'changed the request status of 0624202431 from for pickup to released', '2024-06-24 06:31:11'),
(19, 3, 'changed the request status of 0616202429 from for pickup to released', '2024-06-24 06:31:23'),
(20, 3, 'logged out', '2024-06-24 06:36:16'),
(21, 6, 'logged in', '2024-06-24 06:36:22'),
(22, 6, 'logged out', '2024-06-24 06:40:13'),
(23, 4, 'logged in', '2024-06-24 06:40:21'),
(24, 4, 'logged in', '2024-06-24 06:41:15'),
(25, 4, 'changed the request status of 0624202433 from pending to for payment', '2024-06-24 06:43:05'),
(26, 4, 'logged out', '2024-06-24 06:49:10'),
(27, 4, 'logged in', '2024-06-24 06:49:14'),
(28, 4, 'logged in', '2024-06-24 06:54:11'),
(29, 4, 'logged in', '2024-06-24 06:54:29'),
(30, 4, 'logged in', '2024-06-24 06:56:40'),
(31, 4, 'logged out', '2024-06-24 07:07:13'),
(32, 6, 'logged in', '2024-06-24 07:07:19'),
(33, 6, 'changed the request status of 0624202433 from for payment to for scheduling', '2024-06-24 07:07:32'),
(34, 6, 'logged in', '2024-06-24 07:08:46'),
(35, 3, 'logged in', '2024-06-24 07:25:12'),
(36, 3, 'changed the request status of 0624202434 from pending to for payment', '2024-06-24 07:25:22'),
(37, 3, 'changed the request status of 0624202434 from for payment to for scheduling', '2024-06-24 07:25:32'),
(38, 4, 'logged in', '2024-06-24 07:27:00'),
(39, 4, 'logged out', '2024-06-24 07:27:30'),
(40, 6, 'logged in', '2024-06-24 07:27:36'),
(41, 6, 'logged in', '2024-06-24 07:35:54'),
(42, 6, 'logged in', '2024-06-24 07:36:42'),
(43, 6, 'logged in', '2024-06-24 07:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'Your request status has been updated to \'canceled\'.', 1, '2024-06-02 09:26:28'),
(2, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-02 09:27:10'),
(3, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 09:27:22'),
(4, 1, 'Your request status has been updated to \'for pickup\'.', 1, '2024-06-02 09:28:24'),
(8, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-02 09:29:48'),
(9, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 09:29:51'),
(10, 1, 'Your request status has been updated to \'pending\'.', 1, '2024-06-02 10:00:33'),
(11, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 10:00:42'),
(12, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 10:49:44'),
(13, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-02 10:50:10'),
(14, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 10:50:13'),
(15, 1, 'Your request status has been updated to \'canceled\'.', 1, '2024-06-02 14:06:01'),
(16, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 14:26:41'),
(17, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-02 14:49:08'),
(18, 4, 'Your request status has been updated to \'pending\'.', 1, '2024-06-02 16:16:59'),
(19, 1, 'Your request status has been updated to \'released\'.', 1, '2024-06-02 16:17:40'),
(20, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-02 17:11:13'),
(21, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-03 09:04:08'),
(22, 4, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-03 09:04:21'),
(23, 4, 'Your request status has been updated to \'released\'.', 1, '2024-06-03 09:04:28'),
(24, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-03 09:06:11'),
(26, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-03 09:30:08'),
(29, 1, 'Your request status has been updated to \'released\'.', 1, '2024-06-03 09:39:49'),
(30, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-03 10:07:04'),
(31, 1, 'Your request status has been updated to \'released\'.', 1, '2024-06-03 10:10:26'),
(32, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-03 10:27:26'),
(33, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-03 10:27:28'),
(34, 4, 'You have received a reply for your request.', 1, '2024-06-03 10:35:08'),
(35, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-03 10:38:05'),
(36, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-03 10:43:39'),
(37, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-16 06:48:51'),
(38, 1, 'You have received a reply for your request.', 1, '2024-06-16 08:18:54'),
(39, 1, 'You have received a reply for your request.', 1, '2024-06-16 08:18:56'),
(40, 1, 'You have received a reply for your request.', 1, '2024-06-16 08:18:57'),
(42, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-16 10:49:24'),
(43, 1, 'Your request status has been updated to \'released\'.', 1, '2024-06-16 10:54:47'),
(46, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 11:13:33'),
(47, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 11:17:22'),
(48, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 11:55:49'),
(49, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-16 11:56:43'),
(50, 8, 'Your request status has been updated to \'for payment\'.', 0, '2024-06-16 12:11:56'),
(51, 8, 'Your request status has been updated to \'for scheduling\'.', 0, '2024-06-16 12:12:18'),
(52, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 16:00:48'),
(53, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-16 16:04:51'),
(54, 4, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 16:13:47'),
(55, 4, 'Your request status has been updated to \'pending\'.', 1, '2024-06-16 16:14:19'),
(56, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 16:18:26'),
(57, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-16 16:29:48'),
(58, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-16 16:57:48'),
(59, 1, 'You have received a reply for your request.', 1, '2024-06-16 17:07:40'),
(60, 1, 'You have received a reply for your request.', 1, '2024-06-16 17:07:58'),
(61, 1, 'You have received a reply for your request.', 1, '2024-06-16 17:09:27'),
(62, 1, 'You have received a reply for your request.', 1, '2024-06-16 17:26:53'),
(63, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-23 14:17:03'),
(64, 1, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-24 05:35:51'),
(65, 1, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-24 05:36:04'),
(66, 1, 'Your request status has been updated to \'released\'.', 1, '2024-06-24 05:36:38'),
(67, 4, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-24 05:38:01'),
(68, 4, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-24 05:41:40'),
(69, 5, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-24 06:30:32'),
(70, 5, 'Your request status has been updated to \'released\'.', 1, '2024-06-24 06:31:11'),
(71, 1, 'Your request status has been updated to \'released\'.', 0, '2024-06-24 06:31:23'),
(72, 9, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-24 06:43:05'),
(73, 9, 'Your request status has been updated to \'for scheduling\'.', 0, '2024-06-24 07:07:32'),
(74, 10, 'Your request status has been updated to \'for payment\'.', 1, '2024-06-24 07:25:22'),
(75, 10, 'Your request status has been updated to \'for scheduling\'.', 1, '2024-06-24 07:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','verifying','for payment','for pickup','released','canceled','for scheduling') DEFAULT 'pending',
  `reference_id` varchar(20) DEFAULT NULL,
  `conversation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`conversation`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`attachments`)),
  `schedule_date` date DEFAULT NULL,
  `document_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`document_types`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `document_type`, `file_path`, `message`, `status`, `reference_id`, `conversation`, `attachments`, `schedule_date`, `document_types`, `updated_at`, `created_at`) VALUES
(8, 1, '', NULL, NULL, 'released', '060220248', '[{\"role\":\"student\",\"message\":\"Hello, can you check if you can open the file attachment? \"}]', '[\"uploads\\/Letter Proposal_A4 (1).pdf\"]', '2024-06-02', '[\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(9, 4, '', NULL, NULL, 'for payment', '060220249', '[{\"role\":\"student\",\"message\":\"Can you please check if you can access the attached file? \"},{\"role\":\"student\",\"message\":\"Here\'s another document \"}]', '[\"..\\/5_uploads\\/Test.pdf\",\"..\\/5_uploadsTest.pdf\"]', NULL, '[\"Transcript of Records\",\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(10, 4, '', NULL, NULL, 'for pickup', '0602202410', '[{\"role\":\"student\",\"message\":\"Hello this is the test document \"}]', '[\"..\\/5_uploads\\/Test.docx\"]', '2024-06-24', '[\"Transcript of Records\",\"Transfer Credentials\",\"Diploma\",\"Certificate of Enrollment\\/Registration\",\"Certificate of Grade\",\"Certificate of Study Load\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certification of Good Moral Character\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Good Moral Character\",\"Certified True Copy of Certificate of Graduation\",\"Form 137\"]', '2024-06-24 05:41:53', '2024-06-24 06:56:07'),
(12, 1, '', NULL, NULL, 'released', '0602202412', '[{\"role\":\"student\",\"message\":\"Hello, please see attached file. \\r\\n\"}]', '[\"..\\/5_uploads\\/Group-1-Recitation.pdf\"]', '2024-06-07', '[\"Transcript of Records\",\"Transfer Credentials\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Graduation\",\"Certified True Copy of Certificate of General Weighted Average\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(16, 4, '', NULL, NULL, 'released', '0602202416', '[{\"role\":\"student\",\"message\":\"\"}]', '[\"..\\/5_uploads\\/Test.pdf\"]', NULL, '[\"Diploma\",\"Certificate of Transfer\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(17, 4, '', NULL, NULL, 'for pickup', '0602202417', '[{\"role\":\"student\",\"message\":\"Pleaseeeeeeeee!!!!!!!!!!!!!!!!!!!!!!!!!!!\"}]', '[\"..\\/5_uploads\\/Group-1-Recitation.pdf\"]', '2024-06-17', '[\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(18, 4, '', NULL, NULL, 'for scheduling', '0602202418', '[{\"role\":\"student\",\"message\":\"How is it going? \"},{\"role\":\"admin\",\"message\":\"Okay\"}]', '[\"..\\/5_uploads\\/SIMULATION-Group2.pdf\"]', NULL, '[\"Form 138 A (CTC)\",\"Form 137\"]', '2024-06-24 05:41:40', '2024-06-24 06:56:07'),
(21, 1, '', NULL, NULL, 'released', '0603202421', '[{\"role\":\"student\",\"message\":\"safgagsb\"}]', '[]', '2024-06-02', '[\"Certificate of General Weighted Average\",\"Certificate of Study Load\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(22, 1, '', NULL, NULL, 'for pickup', '0603202422', '[{\"role\":\"student\",\"message\":\"tfuygi;\"}]', '[]', '2024-06-07', '[\"Certificate of Grade\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(24, 1, '', NULL, NULL, 'released', '0616202424', '[{\"role\":\"student\",\"message\":\"Hello, this is my request. \"},{\"role\":\"student\",\"message\":\"Hi, what\'s up?\"}]', '[\"..\\/5_uploads\\/84469b33a7827daa536dd0056749f83e.webp\",\"..\\/5_uploads\"]', '2024-06-17', '[\"Certificate of Graduation\",\"Certificate of General Weighted Average\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(25, 8, '', NULL, NULL, 'for scheduling', '0616202425', '[{\"role\":\"student\",\"message\":\"Please see attached file. \"}]', '[\"..\\/5_uploads\\/Activity2_BethelAlbor.pptx\"]', NULL, '[\"Transfer Credentials\",\"Certificate of Honorable Dismissal\",\"Certificate of General Weighted Average\",\"Certificate of Study Load\"]', '2024-06-23 13:53:16', '2024-06-24 06:56:07'),
(27, 1, '', NULL, NULL, 'released', '0616202427', '[{\"role\":\"student\",\"message\":\"Hi!!\"},{\"role\":\"student\",\"message\":\"Hello! \"},{\"role\":\"admin\",\"message\":\"Is this finished?\"}]', '[\"..\\/5_uploads\\/Activity4_Friend4.png\",\"..\\/5_uploadsActivity2_Result1.png\"]', '2024-06-17', '[\"Certificate of Transfer\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certification of Good Moral Character\"]', '2024-06-24 05:36:38', '2024-06-24 06:56:07'),
(28, 1, '', NULL, NULL, 'for scheduling', '0616202428', '[{\"role\":\"student\",\"message\":\"\"}]', '[]', NULL, '[\"Diploma\"]', '2024-06-24 05:36:04', '2024-06-24 06:56:07'),
(29, 1, '', NULL, NULL, 'released', '0616202429', '[{\"role\":\"student\",\"message\":\"Hi!\"},{\"role\":\"admin\",\"message\":\"please choose a schedule\\r\\n\"},{\"role\":\"admin\",\"message\":\"hello, hurry up\"},{\"role\":\"admin\",\"message\":\"Hi!\\r\\n\"}]', '[\"..\\/5_uploads\\/GroupActivityDay16.txt\"]', '2024-06-17', '[\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\"]', '2024-06-24 06:31:23', '2024-06-24 06:56:07'),
(30, 5, '', NULL, NULL, 'pending', '0624202430', '[{\"role\":\"student\",\"message\":\"Good day, this is my request! \"}]', '[]', NULL, '[\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certification of Good Moral Character\"]', '2024-06-24 06:25:21', '2024-06-24 06:56:07'),
(31, 5, '', NULL, NULL, 'released', '0624202431', '[{\"role\":\"student\",\"message\":\"Please see attached file\"}]', '[\"..\\/5_uploads\\/Albor_quiz3.png\"]', '2024-06-17', '[\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\"]', '2024-06-24 06:31:11', '2024-06-24 06:56:07'),
(32, 9, '', NULL, NULL, 'pending', '0624202432', '[{\"role\":\"student\",\"message\":\"\"}]', '[]', NULL, '[\"Diploma\",\"Certificate of Grade\"]', '2024-06-24 06:42:50', '2024-06-24 06:56:07'),
(33, 9, '', NULL, NULL, 'for scheduling', '0624202433', '[{\"role\":\"student\",\"message\":\"\"}]', '[]', NULL, '[\"Certified True Copy of Certificate of General Weighted Average\",\"Form 138 A (CTC)\",\"Form 137\"]', '2024-06-24 07:07:32', '2024-06-24 06:56:07'),
(34, 10, '', NULL, NULL, 'for pickup', '0624202434', '[{\"role\":\"student\",\"message\":\"Hi, this is my request. \"}]', '[]', '2024-06-25', '[\"Certificate of Honorable Dismissal\"]', '2024-06-24 07:26:17', '2024-06-24 07:24:12'),
(35, 10, '', NULL, NULL, 'pending', '0624202435', '[{\"role\":\"student\",\"message\":\"Hi, I am asking for my Transfer Credentials. \"}]', '[\"..\\/5_uploads\\/ace.png\"]', NULL, '[\"Transfer Credentials (TOR, Honorable Dismissal, Certificate of Good Moral Character, Certificate of Grades, Certificate of Transfer)\"]', '2024-06-24 07:25:01', '2024-06-24 07:25:01');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_slots`
--

CREATE TABLE `schedule_slots` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `available_slots` int(11) NOT NULL DEFAULT 50,
  `statuses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`statuses`)),
  `document_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`document_types`)),
  `courses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`courses`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_slots`
--

INSERT INTO `schedule_slots` (`id`, `date`, `available_slots`, `statuses`, `document_types`, `courses`) VALUES
(7, '2024-06-16', 58, '[\"enrolled\"]', '[\"Certificate of Grade\"]', '[\"CCS\"]'),
(10, '2024-06-17', 44, '[\"enrolled\"]', '[\"Transcript of Records\",\"Transfer Credentials\",\"Diploma\",\"Certificate of Transfer\",\"Certificate of Enrollment\\/Registration\",\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\",\"Certificate of Study Load\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certified True Copy of Transcript of Record\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Good Moral Character\",\"Certified True Copy of Certificate of Graduation\",\"Certified True Copy of Certificate of General Weighted Average\",\"Form 138 A (CTC)\",\"Form 137\"]', '[\"BSOA\",\"BSIT\",\"COA\",\"CCS\",\"CHRM\",\"ABA\",\"AAIS\"]'),
(11, '2024-06-17', 4, '[\"graduated\"]', '[\"Transcript of Records\",\"Transfer Credentials\",\"Diploma\",\"Certificate of Transfer\",\"Certificate of Enrollment\\/Registration\",\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\",\"Certificate of Study Load\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certified True Copy of Transcript of Record\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Good Moral Character\",\"Certified True Copy of Certificate of Graduation\",\"Certified True Copy of Certificate of General Weighted Average\",\"Form 138 A (CTC)\",\"Form 137\"]', '[\"BSOA\",\"BSIT\",\"COA\",\"CCS\",\"CHRM\",\"ABA\",\"AAIS\"]'),
(12, '2024-06-18', 10, '[\"enrolled\"]', '[\"Transcript of Records\",\"Transfer Credentials\",\"Diploma\",\"Certificate of Transfer\",\"Certificate of Enrollment\\/Registration\",\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\",\"Certificate of Study Load\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certified True Copy of Transcript of Record\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Good Moral Character\",\"Certified True Copy of Certificate of Graduation\",\"Certified True Copy of Certificate of General Weighted Average\",\"Form 138 A (CTC)\",\"Form 137\"]', '[\"BSOA\",\"BSIT\",\"COA\",\"CCS\",\"CHRM\",\"ABA\",\"AAIS\"]'),
(14, '2024-06-24', 9, '[\"dropped\"]', '[\"Transcript of Records\",\"Transfer Credentials\",\"Diploma\",\"Certificate of Transfer\",\"Certificate of Enrollment\\/Registration\",\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\",\"Certificate of Study Load\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certified True Copy of Transcript of Record\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Good Moral Character\",\"Certified True Copy of Certificate of Graduation\",\"Certified True Copy of Certificate of General Weighted Average\",\"Form 138 A (CTC)\",\"Form 137\"]', '[\"BSOA\",\"BSIT\",\"COA\",\"CCS\",\"CHRM\",\"ABA\",\"AAIS\"]'),
(15, '2024-06-25', 49, '[\"enrolled\",\"graduating\",\"graduated\",\"dropped\"]', '[\"Transcript of Records\",\"Transfer Credentials\",\"Diploma\",\"Certificate of Transfer\",\"Certificate of Enrollment\\/Registration\",\"Certificate of Grade\",\"Certificate of Honorable Dismissal\",\"Certificate of Graduation\",\"Certificate of General Weighted Average\",\"Certificate of Study Load\",\"Certification-Authentication-Verification (CAV) of Grades of TOR\\/Diploma (CTC)\",\"Certified True Copy of Transcript of Record\",\"Certified True Copy of Diploma\",\"Certified True Copy of Certificate of Good Moral Character\",\"Certified True Copy of Certificate of Graduation\",\"Certified True Copy of Certificate of General Weighted Average\",\"Form 138 A (CTC)\",\"Form 137\"]', '[\"BSOA\",\"BSIT\",\"COA\",\"CCS\",\"CHRM\",\"ABA\",\"AAIS\"]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `birthday` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `status` enum('enrolled','graduating','graduated','dropped') NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `course` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `middle_name`, `birthday`, `address`, `mobile`, `student_id`, `status`, `school_year`, `course`) VALUES
(1, 'bethelalbors@gmail.com', '$2y$10$wy6lLHju1OWYFnyTAUxiFOgSos0hj43juRqR/EooRB6sQHq7NI1oi', 'Bethel', 'Albor', 'Rodriguez', '2001-07-10', 'B1 L2 Ph3 Brgy pinagsama, parang baliw, taguig', '09466510710', '2022-8196', 'enrolled', '2022', 'COA'),
(4, 'gracia@hotmail.com', '$2y$10$9lW.N5TRsLPIgHEx9PRrw.lJZhgKV5dgG/AYVs3Q40UDTZFc4y8H.', 'Gracia', 'Flores', 'Benda', '2004-04-24', 'B34 L1 Delaware Manhattan USA ', '09844875154', '2024-1547', 'dropped', '2024', 'ABA'),
(5, 'jolinaogdang@outlook.com', '$2y$10$5C5esNc3NkqbGABH491qAutC5DaU9TbmXovlzI8IY/f0YkvZ8ZJMq', 'Jolina', 'Ogdang', 'Rodriguez', '2001-08-20', 'B4 L23 PH5 Bavalu, Sto. Rosario-kanluran, Pateros', '0904884517', '2021-1235', 'graduated', '2021', 'ABA'),
(8, 'jgstjrsjnsf@gmail.com', '$2y$10$wZWxk3e2ajX0yU/CQJL.kOOXGL3Yv2VYcFDoeQ9ivs0b.EXFTefKO', 'Juan', 'Dela Cruz', 'Rodriguez', '2001-05-08', 'Blk3 L2 Phs34 Continent', '09784757845', '2027-5188', 'dropped', '2022', 'COA'),
(9, 'villcon@gmail.com', '$2y$10$C70kGZ4OCsjKe0IaS3YVle9o9cphY/3vypfkjrYeOKasqNchkWxU.', 'Villcon ', 'Arizala', 'Mabaho', '2001-04-08', 'Blk3 L2 Phs34 Continent', '09093483832', '2022-7848', 'graduating', '2022', 'COA'),
(10, 'yhenzo@google.com', '$2y$10$1/jwOjaDv.V3drOcIXPY6ulA9U9mciZ6vvPO.d4D81RbaB4OkQcCi', 'Yhenzo', 'Echague', 'Baliw', '1945-11-10', 'B1 L2 Ph3 Villarosa Homes, San Pedro, Laguna', '09594874154', '2024-7842', 'graduated', '2021', 'CHRM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_log`
--
ALTER TABLE `history_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedule_slots`
--
ALTER TABLE `schedule_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `history_log`
--
ALTER TABLE `history_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `schedule_slots`
--
ALTER TABLE `schedule_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history_log`
--
ALTER TABLE `history_log`
  ADD CONSTRAINT `history_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
