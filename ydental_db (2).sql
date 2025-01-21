-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 06, 2025 at 04:31 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ydental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `thecase_id` bigint UNSIGNED NOT NULL,
  `status` enum('بانتظار التأكيد','مؤكد','مكتمل','ملغي') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'بانتظار التأكيد',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_patient_id_foreign` (`patient_id`),
  KEY `appointments_student_id_foreign` (`student_id`),
  KEY `appointments_thecase_id_foreign` (`thecase_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `student_id`, `thecase_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'مكتمل', '2024-12-18 17:47:34', '2024-12-21 03:42:24'),
(2, 1, 1, 2, 'بانتظار التأكيد', '2024-12-18 18:01:07', '2024-12-25 05:18:56'),
(3, 1, 1, 2, 'ملغي', '2024-12-21 07:42:52', '2024-12-28 19:39:15');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1736180537;', 1736180537),
('a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1736180537),
('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1735116255;', 1735116255),
('356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1735116255),
('62dcdddd2131784090e8a5916c260a90678e71b3:timer', 'i:1734975135;', 1734975135),
('62dcdddd2131784090e8a5916c260a90678e71b3', 'i:1;', 1734975135);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'صنعاء', NULL, NULL),
(2, 'تعز', NULL, NULL),
(3, 'عدن', NULL, NULL),
(4, 'ذمار', NULL, NULL),
(5, 'إب', NULL, NULL),
(6, 'عمران', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `complaint_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `complaint_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `complaint_desciption` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `complaint_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `complaints_patient_id_foreign` (`patient_id`),
  KEY `complaints_student_id_foreign` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `patient_id`, `student_id`, `complaint_type`, `complaint_title`, `complaint_desciption`, `complaint_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'ملاحظة', 'عدم التزام', 'الا ريهؤي ؤيهؤياؤي ؤيؤ يع', '2024-12-12', '2024-12-18 19:09:07', '2024-12-18 19:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"812ff3a1-bd78-487c-87a0-74af26abdcf2\",\"displayName\":\"Filament\\\\Notifications\\\\Auth\\\\ResetPassword\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"Filament\\\\Notifications\\\\Auth\\\\ResetPassword\\\":3:{s:3:\\\"url\\\";s:220:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/password-reset\\/reset?email=g.matuq%40gmail.com&token=f259416f6177de2c90b4c33db56b86afb92b17e767300220e001fc4899a7d51f&signature=adb523cd73c8bae4e0d544076c72b2a7ab1756d2eb409ac85f82450280758331\\\";s:5:\\\"token\\\";s:64:\\\"f259416f6177de2c90b4c33db56b86afb92b17e767300220e001fc4899a7d51f\\\";s:2:\\\"id\\\";s:36:\\\"35df5051-02ae-48fd-bca7-7420d685bbef\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1734807418, 1734807418),
(2, 'default', '{\"uuid\":\"1f8bb576-25a4-4c99-85f4-f943a8428f7b\",\"displayName\":\"Filament\\\\Notifications\\\\Auth\\\\ResetPassword\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"Filament\\\\Notifications\\\\Auth\\\\ResetPassword\\\":3:{s:3:\\\"url\\\";s:220:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/password-reset\\/reset?email=g.matuq%40gmail.com&token=7f69d10e4395c2e3d6c3bcc000b3bbf397d30cf240e8f3904e3eb144878586ac&signature=0830f383ce8d6b49dc04cade28d90868597499e3cf457de0a0dac88dadea1328\\\";s:5:\\\"token\\\";s:64:\\\"7f69d10e4395c2e3d6c3bcc000b3bbf397d30cf240e8f3904e3eb144878586ac\\\";s:2:\\\"id\\\";s:36:\\\"981837fa-1d1f-423a-ade7-57b9da42308a\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1734807542, 1734807542),
(3, 'default', '{\"uuid\":\"909e5f9c-809e-4040-986c-b9877a846758\",\"displayName\":\"Filament\\\\Notifications\\\\Auth\\\\ResetPassword\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"Filament\\\\Notifications\\\\Auth\\\\ResetPassword\\\":3:{s:3:\\\"url\\\";s:220:\\\"http:\\/\\/127.0.0.1:8000\\/admin\\/password-reset\\/reset?email=g.matuq%40gmail.com&token=ba0adb72cf38f980b6221e407ace20a2ec34bfc4146f1eb120c9b62e4fdb8712&signature=1a5c15980bbba9f246153fef3e4b13026ba5a97dd3232055660a20c13376cb46\\\";s:5:\\\"token\\\";s:64:\\\"ba0adb72cf38f980b6221e407ace20a2ec34bfc4146f1eb120c9b62e4fdb8712\\\";s:2:\\\"id\\\";s:36:\\\"24660443-9f9a-4322-860f-aea783ef85ff\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1734975076, 1734975076);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_12_10_174854_create_patients_table', 1),
(5, '2024_12_10_175814_create_visits_table', 1),
(6, '2024_12_10_191424_create_services_table', 1),
(7, '2024_12_11_171621_create_complaints_table', 1),
(8, '2024_12_11_204840_create_schedules_table', 1),
(9, '2024_12_11_204936_create_chats_table', 1),
(10, '2024_12_11_204959_create_reviews_table', 1),
(11, '2024_12_14_175618_create_students_table', 1),
(12, '2024_12_14_175622_create_cities_table', 1),
(13, '2024_12_14_180813_create_universities_table', 1),
(14, '2024_12_18_185314_create_appointments_table', 1),
(15, '2024_12_18_203107_create_thecases_table', 1),
(16, '2024_12_24_172719_create_reports_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('g.matuq@gmail.com', '$2y$12$Mu6ivqk.DUxJuyUcFtcySePPBhOKama5ZyLux2uqb1BZs51HZ9f5y', '2024-12-23 14:31:16');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmPassword` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_card` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` int NOT NULL,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userType` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isBlocked` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `password`, `confirmPassword`, `id_card`, `gender`, `date_of_birth`, `phone_number`, `address`, `userType`, `isBlocked`, `created_at`, `updated_at`) VALUES
(1, 'غدير معتوق  زاهر', 'admin@admin.com', '12345678', '12345678', '20221010222', 'ذكر', '2024-12-24', 715423519, 'حدة المدينة شارع  الخمسين', 'مريض', 'محظور', '2024-12-18 17:45:22', '2024-12-31 06:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `report` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_patient_id_foreign` (`patient_id`),
  KEY `reviews_student_id_foreign` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `patient_id`, `student_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 4, 'الطالب تعامله لطيف و يده خفيفة', '2024-12-28 19:52:36', '2024-12-28 19:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `available_date` date NOT NULL,
  `available_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `available_date`, `available_time`, `created_at`, `updated_at`) VALUES
(1, '2024-12-18', '23:44:00', NULL, NULL),
(2, '2024-12-21', '10:37:18', NULL, NULL),
(3, '2024-12-12', '11:53:59', '2024-12-21 05:54:20', '2024-12-21 05:54:20');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'حشو', 'images/OIP.jpg', '2024-12-18 17:43:19', '2024-12-18 17:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('yeo0qjsKRUuQ0G6KBaCAI4uc77dK7fOS6ulNUAu7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYVNqMUppakpJdUZ5Smg5N1ZUVXh6MjhPQnp5OVJkVlJGekJvMzh5NiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hcHBvaW50bWVudHMvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkcnpQcUxlN3NwOG9kUFNEV1M2aFFIT1dzRXZyNVVHU3Y1anBWV1Jra0xhZUpFcnZKVlJKejIiO30=', 1736180969);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_id` bigint UNSIGNED NOT NULL,
  `university_id` bigint UNSIGNED NOT NULL,
  `student_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userType` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confirmPassword` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` int NOT NULL,
  `university_card_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `university_card_image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isBlocked` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `students_city_id_foreign` (`city_id`),
  KEY `students_university_id_foreign` (`university_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `city_id`, `university_id`, `student_image`, `userType`, `name`, `email`, `password`, `confirmPassword`, `gender`, `level`, `description`, `phone_number`, `university_card_number`, `university_card_image`, `isBlocked`, `created_at`, `updated_at`) VALUES
(1, 2, 23, 'images/pha.jpg', 'طالب', 'سدرة', 'admin@admin.com', '12345678', '12345678', 'أنثى', 'الرابع', 'أمتلك خبرة في مجال الحشوات التجميلية ', 77777777, '202210102338', NULL, 'نشط', '2024-12-18 17:45:55', '2024-12-31 06:01:19'),
(2, 4, 34, 'images/pha.jpg', 'طالب', 'هديل', 'hhh@gmail.com', '11111111', '11111111', 'أنثى', 'الثاني', 'أدرس في جامعة ذمار  , أمتلك خبرة في مجال الأسنان خصوصا الحشو', 715423516, '202210102339', NULL, 'محظور', '2024-12-25 05:43:20', '2024-12-31 05:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `thecases`
--

DROP TABLE IF EXISTS `thecases`;
CREATE TABLE IF NOT EXISTS `thecases` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_id` bigint UNSIGNED NOT NULL,
  `schedules_id` bigint UNSIGNED NOT NULL,
  `procedure` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` double NOT NULL,
  `min_age` int NOT NULL,
  `max_age` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `thecases_service_id_foreign` (`service_id`),
  KEY `thecases_schedules_id_foreign` (`schedules_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thecases`
--

INSERT INTO `thecases` (`id`, `service_id`, `schedules_id`, `procedure`, `gender`, `description`, `cost`, `min_age`, `max_age`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'خلع الزفت', 'أنثى', 'ثيبثبثب ب قث لبقل قث', 0, 9, 22, NULL, NULL),
(2, 1, 3, 'حشو امامي', 'ذكر', 'يييييييييييييييييييي', 0, 9, 22, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
CREATE TABLE IF NOT EXISTS `universities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `universities_city_id_foreign` (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `city_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'جامعة العلوم والتكنولوجيا', NULL, NULL),
(2, 1, 'جامعة صنعاء', NULL, NULL),
(3, 1, 'جامعة الرازي', NULL, NULL),
(4, 1, 'جامعة ابن النفيس', NULL, NULL),
(5, 1, 'جامعة سبأ', NULL, NULL),
(6, 1, 'جامعة الناصر ', NULL, NULL),
(7, 1, 'جامعة إقرأ', NULL, NULL),
(8, 1, 'جامعة الحكمة', NULL, NULL),
(9, 1, ' جامعة الملكة أروى ', NULL, NULL),
(10, 1, 'الجامعة العربية للعلوم والتقنية', NULL, NULL),
(11, 1, 'جامعة العلوم الحديثة', NULL, NULL),
(12, 1, 'جامعة دار السلام', NULL, NULL),
(13, 1, 'جامعة الرشيد الذكية ', NULL, NULL),
(14, 1, 'جامعة الجيل الجديد', NULL, NULL),
(15, 1, 'الجامعة البريطانية', NULL, NULL),
(16, 1, 'جامعة المستقبل', NULL, NULL),
(17, 1, 'الجامعة الإمارتية الدولية', NULL, NULL),
(18, 1, 'الجامعة اليمنية الإردنية', NULL, NULL),
(19, 1, 'الجامعة اليمنية', NULL, NULL),
(20, 1, 'جامعة المستقبل', NULL, NULL),
(21, 1, 'الجامعة اللبنانية', NULL, NULL),
(22, 1, ' جامعة الاندلس للعلوم والتقنية', NULL, NULL),
(23, 2, ' جامعة تعز', NULL, NULL),
(24, 2, ' جامعة العلوم والتكنولوجيا', NULL, NULL),
(25, 2, 'جامعة الرواد', NULL, NULL),
(26, 2, ' جامعةالعطاء للعلوم والتكنولوجيا', NULL, NULL),
(27, 2, 'كلية22مايو للعلوم الطبية والتطبيقية', NULL, NULL),
(28, 2, 'الجامعة الوطنية', NULL, NULL),
(29, 2, ' جامعة السعيد', NULL, NULL),
(30, 3, ' جامعة عدن', NULL, NULL),
(31, 3, ' جامعة الحضارة', NULL, NULL),
(32, 3, '  جامعة ابن خلدون', NULL, NULL),
(33, 3, ' جامعة العادل', NULL, NULL),
(34, 4, ' جامعة ذمار', NULL, NULL),
(35, 4, ' جامعة الحكمة', NULL, NULL),
(36, 4, '  جامعة السعيدة', NULL, NULL),
(37, 4, ' جامعة جينيس للعلوم والتكنولوجيا', NULL, NULL),
(38, 5, ' جامعة إب', NULL, NULL),
(39, 5, 'جامعة القلم للعلوم الإنسانية والتطبيقية', NULL, NULL),
(40, 5, 'جامعة العلوم والتكنولوجيا', NULL, NULL),
(41, 5, ' جامعة الجزيرة', NULL, NULL),
(42, 5, 'الجامعة الماليزية الدولية', NULL, NULL),
(43, 6, 'جامعة عمران ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ghadeer Zaher', 'admin@admin.com', 0, NULL, '$2y$12$rzPqLe7sp8odPSDWS6hQHOWsEvr5UGSv5jpVWRkkLaeJErvJVRJz2', NULL, '2024-12-18 17:38:37', '2024-12-23 15:40:25'),
(2, 'غدير معتوق  زاهر', 'g.matuq@gmail.com', 0, NULL, '$2y$12$GNnFSDo.EwDjH4pfbusC1eaAtN1NS5cou47N/5RyF2/m.FsjzNa/O', NULL, '2024-12-18 19:11:04', '2024-12-23 15:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `visit_date` date NOT NULL,
  `procedure` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('غير مكتملة','مكتملة','ملغية') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'غير مكتملة',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visits_appointment_id_foreign` (`appointment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `appointment_id`, `visit_date`, `procedure`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-11', 'خلع ضرس العقل', 'الا ىبهثثي يسؤه ؤاعساؤ هسخء ', 'مكتملة', '2024-12-18 19:02:34', '2024-12-25 06:06:26'),
(2, 2, '2024-12-11', 'خلع ضرس العقل', 'du8ifueibfbe euife ei feyfeiu ', 'غير مكتملة', '2024-12-25 06:06:05', '2024-12-25 06:06:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
