-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Waktu pembuatan: 09 Okt 2026 pada 05.25
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skill_gap_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `analysis_history`
--

CREATE TABLE `analysis_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `match_percentage` decimal(5,2) NOT NULL,
  `matched_skills` text DEFAULT NULL,
  `gap_skills` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `analysis_history`
--

INSERT INTO `analysis_history` (`id`, `user_id`, `match_percentage`, `matched_skills`, `gap_skills`, `created_at`) VALUES
(1, 5, 3.00, '[{\"skill_name\":\"Cybersecurity\",\"matched_with\":\"Cybersecurity\"},{\"skill_name\":\"Cloud Computing\",\"matched_with\":\"Cloud Computing\"},{\"skill_name\":\"C#\",\"matched_with\":\"C#\"},{\"skill_name\":\"C++\",\"matched_with\":\"C++\"}]', '[\"PHP\",\"Laravel\",\"Python\",\"Java\",\"JavaScript\",\"MySQL\",\"React\",\"Vue.js\",\"Docker\",\"AWS\",\"Git\",\"Linux\",\"Communication\",\"Teamwork\",\"Problem Solving\",\"Leadership\",\"Agile\",\"Scrum\",\"Node.js\",\"SQL\",\"CodeIgniter\",\"Symfony\",\"Golang\",\"PostgreSQL\",\"MongoDB\",\"Redis\",\"Angular\",\"Bootstrap\",\"Spring Boot\",\"Kubernetes\",\"Jenkins\",\"Android\",\"iOS\",\"Flutter\",\"React Native\",\"Network\",\"Cisco\",\"Mikrotik\",\"Firewall\",\"UI\\/UX\",\"Figma\",\"Testing\",\"Data Analysis\",\"Machine Learning\",\"DevOps\",\"IT Support\",\"WordPress\",\"SEO\",\"ERP\",\"CRM\",\"Odoo\",\"SAP\",\"TypeScript\",\"Next.js\",\"Nuxt.js\",\"Express.js\",\"NestJS\",\"Django\",\"Flask\",\"Ruby\",\"ASP.NET\",\"Kotlin\",\"Swift\",\"jQuery\",\"Sass\",\"Tailwind\",\"Firebase\",\"SQLite\",\"Oracle\",\"MSSQL\",\"NoSQL\",\"GCP\",\"Azure\",\"Ansible\",\"Terraform\",\"GitHub\",\"GitLab\",\"Bitbucket\",\"Jira\",\"Trello\",\"QA\",\"Selenium\",\"Tableau\",\"Power BI\",\"Hadoop\",\"Spark\",\"CMS\",\"Digital Marketing\",\"IoT\",\"Embedded\",\"Help Desk\",\"System Administrator\",\"Project Management\",\"Full Stack\",\"Frontend\",\"Backend\",\"Software Engineer\",\"Web Developer\",\"Mobile Developer\",\"Cloud Engineer\",\"Network Engineer\",\"Security Engineer\",\"R\",\"VB.NET\",\"Xamarin\",\"Ionic\",\"Cordova\",\"InVision\",\"Photoshop\",\"Illustrator\",\"Adobe XD\",\"Sketch\",\"Asana\",\"Kanban\",\"JUnit\",\"Ethical Hacking\",\"Penetration Testing\",\"Data Mining\",\"Business Analysis\",\"Business Process\"]', '2026-06-18 18:07:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `industry_skills`
--

CREATE TABLE `industry_skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `demand_level` enum('Low','Medium','High') DEFAULT 'Medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `industry_skills`
--

INSERT INTO `industry_skills` (`id`, `skill_name`, `category`, `demand_level`, `created_at`) VALUES
(1, 'PHP', NULL, 'High', '2026-06-17 16:31:46'),
(2, 'Laravel', NULL, 'High', '2026-06-17 16:31:46'),
(3, 'Python', NULL, 'High', '2026-06-17 16:31:46'),
(4, 'Java', NULL, 'High', '2026-06-17 16:31:46'),
(5, 'JavaScript', NULL, 'High', '2026-06-17 16:31:46'),
(6, 'MySQL', NULL, 'High', '2026-06-17 16:31:46'),
(7, 'React', NULL, 'High', '2026-06-17 16:31:46'),
(8, 'Vue.js', NULL, 'High', '2026-06-17 16:31:46'),
(9, 'Docker', NULL, 'High', '2026-06-17 16:31:46'),
(10, 'AWS', NULL, 'High', '2026-06-17 16:31:46'),
(11, 'Git', NULL, 'High', '2026-06-17 16:31:46'),
(12, 'Linux', NULL, 'High', '2026-06-17 16:31:46'),
(13, 'Communication', NULL, 'High', '2026-06-17 16:31:46'),
(14, 'Teamwork', NULL, 'High', '2026-06-17 16:31:46'),
(15, 'Problem Solving', NULL, 'High', '2026-06-17 16:31:46'),
(16, 'Leadership', NULL, 'High', '2026-06-17 16:31:46'),
(17, 'Agile', NULL, 'High', '2026-06-17 16:31:46'),
(18, 'Scrum', NULL, 'High', '2026-06-17 16:31:46'),
(19, 'Node.js', NULL, 'High', '2026-06-17 16:31:46'),
(20, 'SQL', NULL, 'High', '2026-06-17 16:31:46'),
(21, 'CodeIgniter', NULL, 'Medium', '2026-06-17 16:31:46'),
(22, 'Symfony', NULL, 'Medium', '2026-06-17 16:31:46'),
(23, 'Golang', NULL, 'Medium', '2026-06-17 16:31:46'),
(24, 'PostgreSQL', NULL, 'Medium', '2026-06-17 16:31:46'),
(25, 'MongoDB', NULL, 'Medium', '2026-06-17 16:31:46'),
(26, 'Redis', NULL, 'Medium', '2026-06-17 16:31:46'),
(27, 'Angular', NULL, 'Medium', '2026-06-17 16:31:46'),
(28, 'Bootstrap', NULL, 'Medium', '2026-06-17 16:31:46'),
(29, 'Spring Boot', NULL, 'Medium', '2026-06-17 16:31:46'),
(30, 'Kubernetes', NULL, 'Medium', '2026-06-17 16:31:46'),
(31, 'Jenkins', NULL, 'Medium', '2026-06-17 16:31:46'),
(32, 'Android', NULL, 'Medium', '2026-06-17 16:31:46'),
(33, 'iOS', NULL, 'Medium', '2026-06-17 16:31:46'),
(34, 'Flutter', NULL, 'Medium', '2026-06-17 16:31:46'),
(35, 'React Native', NULL, 'Medium', '2026-06-17 16:31:46'),
(36, 'Cybersecurity', NULL, 'Medium', '2026-06-17 16:31:46'),
(37, 'Network', NULL, 'Medium', '2026-06-17 16:31:46'),
(38, 'Cisco', NULL, 'Medium', '2026-06-17 16:31:46'),
(39, 'Mikrotik', NULL, 'Medium', '2026-06-17 16:31:46'),
(40, 'Firewall', NULL, 'Medium', '2026-06-17 16:31:46'),
(41, 'UI/UX', NULL, 'Medium', '2026-06-17 16:31:46'),
(42, 'Figma', NULL, 'Medium', '2026-06-17 16:31:46'),
(43, 'Testing', NULL, 'Medium', '2026-06-17 16:31:46'),
(44, 'Data Analysis', NULL, 'Medium', '2026-06-17 16:31:46'),
(45, 'Machine Learning', NULL, 'Medium', '2026-06-17 16:31:46'),
(46, 'Cloud Computing', NULL, 'Medium', '2026-06-17 16:31:46'),
(47, 'DevOps', NULL, 'Medium', '2026-06-17 16:31:46'),
(48, 'IT Support', NULL, 'Medium', '2026-06-17 16:31:46'),
(49, 'WordPress', NULL, 'Medium', '2026-06-17 16:31:46'),
(50, 'SEO', NULL, 'Medium', '2026-06-17 16:31:46'),
(51, 'ERP', NULL, 'Medium', '2026-06-17 16:31:46'),
(52, 'CRM', NULL, 'Medium', '2026-06-17 16:31:46'),
(53, 'Odoo', NULL, 'Medium', '2026-06-17 16:31:46'),
(54, 'SAP', NULL, 'Medium', '2026-06-17 16:31:46'),
(55, 'TypeScript', NULL, 'Medium', '2026-06-17 16:31:46'),
(56, 'Next.js', NULL, 'Medium', '2026-06-17 16:31:46'),
(57, 'Nuxt.js', NULL, 'Medium', '2026-06-17 16:31:46'),
(58, 'Express.js', NULL, 'Medium', '2026-06-17 16:31:46'),
(59, 'NestJS', NULL, 'Medium', '2026-06-17 16:31:46'),
(60, 'Django', NULL, 'Medium', '2026-06-17 16:31:46'),
(61, 'Flask', NULL, 'Medium', '2026-06-17 16:31:46'),
(62, 'Ruby', NULL, 'Medium', '2026-06-17 16:31:46'),
(63, 'C#', NULL, 'Medium', '2026-06-17 16:31:46'),
(64, 'ASP.NET', NULL, 'Medium', '2026-06-17 16:31:46'),
(65, 'C++', NULL, 'Medium', '2026-06-17 16:31:46'),
(66, 'Kotlin', NULL, 'Medium', '2026-06-17 16:31:46'),
(67, 'Swift', NULL, 'Medium', '2026-06-17 16:31:46'),
(68, 'jQuery', NULL, 'Medium', '2026-06-17 16:31:46'),
(69, 'Sass', NULL, 'Medium', '2026-06-17 16:31:46'),
(70, 'Tailwind', NULL, 'Medium', '2026-06-17 16:31:46'),
(71, 'Firebase', NULL, 'Medium', '2026-06-17 16:31:46'),
(72, 'SQLite', NULL, 'Medium', '2026-06-17 16:31:46'),
(73, 'Oracle', NULL, 'Medium', '2026-06-17 16:31:46'),
(74, 'MSSQL', NULL, 'Medium', '2026-06-17 16:31:46'),
(75, 'NoSQL', NULL, 'Medium', '2026-06-17 16:31:46'),
(76, 'GCP', NULL, 'Medium', '2026-06-17 16:31:46'),
(77, 'Azure', NULL, 'Medium', '2026-06-17 16:31:46'),
(78, 'Ansible', NULL, 'Medium', '2026-06-17 16:31:46'),
(79, 'Terraform', NULL, 'Medium', '2026-06-17 16:31:46'),
(80, 'GitHub', NULL, 'Medium', '2026-06-17 16:31:46'),
(81, 'GitLab', NULL, 'Medium', '2026-06-17 16:31:46'),
(82, 'Bitbucket', NULL, 'Medium', '2026-06-17 16:31:46'),
(83, 'Jira', NULL, 'Medium', '2026-06-17 16:31:46'),
(84, 'Trello', NULL, 'Medium', '2026-06-17 16:31:46'),
(85, 'QA', NULL, 'Medium', '2026-06-17 16:31:46'),
(86, 'Selenium', NULL, 'Medium', '2026-06-17 16:31:46'),
(87, 'Tableau', NULL, 'Medium', '2026-06-17 16:31:46'),
(88, 'Power BI', NULL, 'Medium', '2026-06-17 16:31:46'),
(89, 'Hadoop', NULL, 'Medium', '2026-06-17 16:31:46'),
(90, 'Spark', NULL, 'Medium', '2026-06-17 16:31:46'),
(91, 'CMS', NULL, 'Medium', '2026-06-17 16:31:46'),
(92, 'Digital Marketing', NULL, 'Medium', '2026-06-17 16:31:46'),
(93, 'IoT', NULL, 'Medium', '2026-06-17 16:31:46'),
(94, 'Embedded', NULL, 'Medium', '2026-06-17 16:31:46'),
(95, 'Help Desk', NULL, 'Medium', '2026-06-17 16:31:46'),
(96, 'System Administrator', NULL, 'Medium', '2026-06-17 16:31:46'),
(97, 'Project Management', NULL, 'Medium', '2026-06-17 16:31:46'),
(98, 'Full Stack', NULL, 'Medium', '2026-06-17 16:31:46'),
(99, 'Frontend', NULL, 'Medium', '2026-06-17 16:31:46'),
(100, 'Backend', NULL, 'Medium', '2026-06-17 16:31:46'),
(101, 'Software Engineer', NULL, 'Medium', '2026-06-17 16:31:46'),
(102, 'Web Developer', NULL, 'Medium', '2026-06-17 16:31:46'),
(103, 'Mobile Developer', NULL, 'Medium', '2026-06-17 16:31:46'),
(104, 'Cloud Engineer', NULL, 'Medium', '2026-06-17 16:31:46'),
(105, 'Network Engineer', NULL, 'Medium', '2026-06-17 16:31:46'),
(106, 'Security Engineer', NULL, 'Medium', '2026-06-17 16:31:46'),
(107, 'R', NULL, 'Low', '2026-06-17 16:31:46'),
(108, 'VB.NET', NULL, 'Low', '2026-06-17 16:31:46'),
(109, 'Xamarin', NULL, 'Low', '2026-06-17 16:31:46'),
(110, 'Ionic', NULL, 'Low', '2026-06-17 16:31:46'),
(111, 'Cordova', NULL, 'Low', '2026-06-17 16:31:46'),
(112, 'InVision', NULL, 'Low', '2026-06-17 16:31:46'),
(113, 'Photoshop', NULL, 'Low', '2026-06-17 16:31:46'),
(114, 'Illustrator', NULL, 'Low', '2026-06-17 16:31:46'),
(115, 'Adobe XD', NULL, 'Low', '2026-06-17 16:31:46'),
(116, 'Sketch', NULL, 'Low', '2026-06-17 16:31:46'),
(117, 'Asana', NULL, 'Low', '2026-06-17 16:31:46'),
(118, 'Kanban', NULL, 'Low', '2026-06-17 16:31:46'),
(119, 'JUnit', NULL, 'Low', '2026-06-17 16:31:46'),
(120, 'Ethical Hacking', NULL, 'Low', '2026-06-17 16:31:46'),
(121, 'Penetration Testing', NULL, 'Low', '2026-06-17 16:31:46'),
(122, 'Data Mining', NULL, 'Low', '2026-06-17 16:31:46'),
(123, 'Business Analysis', NULL, 'Low', '2026-06-17 16:31:46'),
(124, 'Business Process', NULL, 'Low', '2026-06-17 16:31:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `skills`
--

INSERT INTO `skills` (`id`, `skill_name`, `category`, `created_at`) VALUES
(1, 'Adaptability', NULL, '2026-06-17 16:18:03'),
(2, 'Blockchain', NULL, '2026-06-17 16:18:03'),
(3, 'C#', NULL, '2026-06-17 16:18:03'),
(4, 'C++', NULL, '2026-06-17 16:18:03'),
(5, 'Cloud Computing', NULL, '2026-06-17 16:18:03'),
(6, 'Communication', NULL, '2026-06-17 16:18:03'),
(7, 'Cybersecurity', NULL, '2026-06-17 16:18:03'),
(8, 'Data Analysis', NULL, '2026-06-17 16:18:03'),
(9, 'Go', NULL, '2026-06-17 16:18:03'),
(10, 'Java', NULL, '2026-06-17 16:18:03'),
(11, 'JavaScript', NULL, '2026-06-17 16:18:03'),
(12, 'Leadership', NULL, '2026-06-17 16:18:03'),
(13, 'Machine Learning', NULL, '2026-06-17 16:18:03'),
(14, 'Problem-Solving', NULL, '2026-06-17 16:18:03'),
(15, 'Python', NULL, '2026-06-17 16:18:03'),
(16, 'R', NULL, '2026-06-17 16:18:03'),
(17, 'SQL', NULL, '2026-06-17 16:18:03'),
(18, 'Teamwork', NULL, '2026-06-17 16:18:03'),
(19, 'Time Management', NULL, '2026-06-17 16:18:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trending_jobs`
--

CREATE TABLE `trending_jobs` (
  `id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `trend_score` int(11) NOT NULL DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trending_jobs`
--

INSERT INTO `trending_jobs` (`id`, `job_title`, `company_name`, `trend_score`, `category`, `updated_at`) VALUES
(1, 'AI Engineer', 'Gojek', 95, 'Software Development', '2026-06-17 16:58:08'),
(2, 'Machine Learning Engineer', 'Tokopedia', 92, 'Software Development', '2026-06-17 16:58:08'),
(3, 'Data Scientist', 'Traveloka', 90, 'Data Science', '2026-06-17 16:58:08'),
(4, 'Full Stack Developer', 'Shopee', 88, 'Software Development', '2026-06-17 16:58:08'),
(5, 'Backend Engineer', 'Bukalapak', 85, 'Software Development', '2026-06-17 16:58:08'),
(6, 'Frontend Engineer', 'Gojek', 83, 'Software Development', '2026-06-17 16:58:08'),
(7, 'DevOps Engineer', 'Tokopedia', 90, 'DevOps', '2026-06-17 16:58:08'),
(8, 'Site Reliability Engineer', 'Traveloka', 82, 'DevOps', '2026-06-17 16:58:08'),
(9, 'Cloud Architect', 'AWS Indonesia', 88, 'Cloud', '2026-06-17 16:58:08'),
(10, 'Software Engineer', 'Google Indonesia', 85, 'Software Development', '2026-06-17 16:58:08'),
(11, 'Data Engineer', 'Gojek', 87, 'Data', '2026-06-17 16:58:08'),
(12, 'Data Analyst', 'Tokopedia', 84, 'Data', '2026-06-17 16:58:08'),
(13, 'Business Intelligence Analyst', 'Shopee', 80, 'Data', '2026-06-17 16:58:08'),
(14, 'Data Architect', 'Traveloka', 82, 'Data', '2026-06-17 16:58:08'),
(15, 'Cybersecurity Analyst', 'Cisco Indonesia', 85, 'Security', '2026-06-17 16:58:08'),
(16, 'Security Engineer', 'Bukalapak', 83, 'Security', '2026-06-17 16:58:08'),
(17, 'Penetration Tester', 'Gojek', 80, 'Security', '2026-06-17 16:58:08'),
(18, 'Cloud Security Architect', 'AWS Indonesia', 82, 'Security', '2026-06-17 16:58:08'),
(19, 'Information Security Manager', 'Bank BCA', 78, 'Security', '2026-06-17 16:58:08'),
(20, 'Mobile Developer - Android', 'Gojek', 82, 'Mobile', '2026-06-17 16:58:08'),
(21, 'Flutter Developer', 'Tokopedia', 80, 'Mobile', '2026-06-17 16:58:08'),
(22, 'React Native Developer', 'Shopee', 78, 'Mobile', '2026-06-17 16:58:08'),
(23, 'iOS Developer', 'Traveloka', 76, 'Mobile', '2026-06-17 16:58:08'),
(24, 'Product Manager', 'Gojek', 85, 'Project Management', '2026-06-17 16:58:08'),
(25, 'Agile Project Manager', 'Tokopedia', 80, 'Project Management', '2026-06-17 16:58:08'),
(26, 'Scrum Master', 'Bukalapak', 78, 'Project Management', '2026-06-17 16:58:08'),
(27, 'Technical Project Manager', 'Traveloka', 76, 'Project Management', '2026-06-17 16:58:08'),
(28, 'UI/UX Designer', 'Shopee', 88, 'Design', '2026-06-17 16:58:08'),
(29, 'Product Designer', 'Gojek', 85, 'Design', '2026-06-17 16:58:08'),
(30, 'Interaction Designer', 'Tokopedia', 80, 'Design', '2026-06-17 16:58:08'),
(31, 'UX Researcher', 'Traveloka', 78, 'Design', '2026-06-17 16:58:08'),
(32, 'IT Support Specialist', 'Bank Mandiri', 72, 'Support', '2026-06-17 16:58:08'),
(33, 'System Administrator', 'Telkom Indonesia', 75, 'Support', '2026-06-17 16:58:08'),
(34, 'Network Engineer', 'Cisco Indonesia', 78, 'Networking', '2026-06-17 16:58:08'),
(35, 'Cloud Engineer', 'AWS Indonesia', 85, 'Cloud', '2026-06-17 16:58:08'),
(36, 'ERP Consultant', 'SAP Indonesia', 75, 'Business', '2026-06-17 16:58:08'),
(37, 'Business Analyst', 'Gojek', 82, 'Business', '2026-06-17 16:58:08'),
(38, 'System Analyst', 'Tokopedia', 80, 'Business', '2026-06-17 16:58:08'),
(39, 'Digital Transformation Specialist', 'Telkom Indonesia', 78, 'Business', '2026-06-17 16:58:08'),
(40, 'NLP Engineer', 'Gojek', 85, 'AI', '2026-06-17 16:58:08'),
(41, 'Computer Vision Engineer', 'Tokopedia', 82, 'AI', '2026-06-17 16:58:08'),
(42, 'Robotics Engineer', 'Traveloka', 75, 'AI', '2026-06-17 16:58:08'),
(43, 'IoT Solutions Architect', 'Telkom Indonesia', 76, 'IoT', '2026-06-17 16:58:08'),
(44, 'Blockchain Developer', 'Gojek', 78, 'Blockchain', '2026-06-17 16:58:08'),
(45, 'Game Developer', 'Agate Studio', 72, 'Game Development', '2026-06-17 16:58:08'),
(46, 'QA Engineer', 'Shopee', 78, 'QA', '2026-06-17 16:58:08'),
(47, 'Technical Writer', 'Gojek', 70, 'Documentation', '2026-06-17 16:58:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trending_skills`
--

CREATE TABLE `trending_skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL,
  `trend_score` int(11) NOT NULL DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trending_skills`
--

INSERT INTO `trending_skills` (`id`, `skill_name`, `trend_score`, `category`, `updated_at`) VALUES
(1, 'Python', 95, 'Programming', '2026-06-17 17:09:53'),
(2, 'JavaScript', 90, 'Programming', '2026-06-17 17:09:53'),
(3, 'TypeScript', 85, 'Programming', '2026-06-17 17:09:53'),
(4, 'Java', 80, 'Programming', '2026-06-17 17:09:53'),
(5, 'Go', 75, 'Programming', '2026-06-17 17:09:53'),
(6, 'Rust', 70, 'Programming', '2026-06-17 17:09:53'),
(10, 'React', 92, 'Frontend Framework', '2026-06-17 17:09:53'),
(11, 'Vue.js', 85, 'Frontend Framework', '2026-06-17 17:09:53'),
(12, 'Angular', 80, 'Frontend Framework', '2026-06-17 17:09:53'),
(13, 'Next.js', 82, 'Frontend Framework', '2026-06-17 17:09:53'),
(14, 'Tailwind CSS', 75, 'Frontend', '2026-06-17 17:09:53'),
(15, 'SASS', 65, 'Frontend', '2026-06-17 17:09:53'),
(16, 'Node.js', 88, 'Backend', '2026-06-17 17:09:53'),
(17, 'Laravel', 82, 'Backend Framework', '2026-06-17 17:09:53'),
(18, 'Django', 80, 'Backend Framework', '2026-06-17 17:09:53'),
(19, 'Spring Boot', 78, 'Backend Framework', '2026-06-17 17:09:53'),
(20, 'Express.js', 75, 'Backend Framework', '2026-06-17 17:09:53'),
(21, 'MySQL', 85, 'Database', '2026-06-17 17:09:53'),
(22, 'PostgreSQL', 82, 'Database', '2026-06-17 17:09:53'),
(23, 'MongoDB', 78, 'Database NoSQL', '2026-06-17 17:09:53'),
(24, 'Redis', 72, 'Database Cache', '2026-06-17 17:09:53'),
(25, 'Elasticsearch', 65, 'Database Search', '2026-06-17 17:09:53'),
(26, 'Docker', 90, 'DevOps', '2026-06-17 17:09:53'),
(27, 'Kubernetes', 88, 'DevOps', '2026-06-17 17:09:53'),
(28, 'AWS', 92, 'Cloud', '2026-06-17 17:09:53'),
(29, 'GCP', 80, 'Cloud', '2026-06-17 17:09:53'),
(30, 'Azure', 78, 'Cloud', '2026-06-17 17:09:53'),
(31, 'Terraform', 75, 'DevOps', '2026-06-17 17:09:53'),
(32, 'Jenkins', 70, 'DevOps', '2026-06-17 17:09:53'),
(33, 'Linux', 85, 'OS', '2026-06-17 17:09:53'),
(34, 'Machine Learning', 94, 'Data Science', '2026-06-17 17:09:53'),
(35, 'Deep Learning', 88, 'Data Science', '2026-06-17 17:09:53'),
(36, 'Artificial Intelligence', 90, 'Data Science', '2026-06-17 17:09:53'),
(37, 'Data Analysis', 85, 'Data Science', '2026-06-17 17:09:53'),
(38, 'Data Visualization', 78, 'Data Science', '2026-06-17 17:09:53'),
(39, 'Tableau', 72, 'Data Science', '2026-06-17 17:09:53'),
(40, 'Power BI', 70, 'Data Science', '2026-06-17 17:09:53'),
(41, 'PyTorch', 80, 'Data Science', '2026-06-17 17:09:53'),
(42, 'TensorFlow', 78, 'Data Science', '2026-06-17 17:09:53'),
(43, 'Natural Language Processing', 75, 'Data Science', '2026-06-17 17:09:53'),
(44, 'Computer Vision', 72, 'Data Science', '2026-06-17 17:09:53'),
(45, 'Cybersecurity', 92, 'Security', '2026-06-17 17:09:53'),
(46, 'Ethical Hacking', 85, 'Security', '2026-06-17 17:09:53'),
(47, 'Penetration Testing', 82, 'Security', '2026-06-17 17:09:53'),
(48, 'Information Security', 80, 'Security', '2026-06-17 17:09:53'),
(49, 'Cloud Security', 78, 'Security', '2026-06-17 17:09:53'),
(50, 'Communication', 90, 'Soft Skills', '2026-06-17 17:09:53'),
(51, 'Problem Solving', 88, 'Soft Skills', '2026-06-17 17:09:53'),
(52, 'Leadership', 85, 'Soft Skills', '2026-06-17 17:09:53'),
(53, 'Teamwork', 85, 'Soft Skills', '2026-06-17 17:09:53'),
(54, 'Adaptability', 82, 'Soft Skills', '2026-06-17 17:09:53'),
(55, 'Critical Thinking', 80, 'Soft Skills', '2026-06-17 17:09:53'),
(56, 'Time Management', 78, 'Soft Skills', '2026-06-17 17:09:53'),
(57, 'Collaboration', 78, 'Soft Skills', '2026-06-17 17:09:53'),
(58, 'Emotional Intelligence', 75, 'Soft Skills', '2026-06-17 17:09:53'),
(59, 'Creative Thinking', 72, 'Soft Skills', '2026-06-17 17:09:53'),
(60, 'Cisco', 75, 'Networking', '2026-06-17 17:09:53'),
(61, 'Mikrotik', 72, 'Networking', '2026-06-17 17:09:53'),
(62, 'Network Security', 78, 'Networking', '2026-06-17 17:09:53'),
(63, 'SD-WAN', 70, 'Networking', '2026-06-17 17:09:53'),
(64, 'Firewall', 76, 'Networking', '2026-06-17 17:09:53'),
(65, 'UI/UX', 88, 'Design', '2026-06-17 17:09:53'),
(66, 'Figma', 82, 'Design Tools', '2026-06-17 17:09:53'),
(67, 'Adobe XD', 75, 'Design Tools', '2026-06-17 17:09:53'),
(68, 'Prototyping', 72, 'Design', '2026-06-17 17:09:53'),
(69, 'Flutter', 85, 'Mobile Framework', '2026-06-17 17:09:53'),
(70, 'React Native', 82, 'Mobile Framework', '2026-06-17 17:09:53'),
(71, 'Android', 78, 'Mobile Development', '2026-06-17 17:09:53'),
(72, 'iOS', 76, 'Mobile Development', '2026-06-17 17:09:53'),
(73, 'ERP', 75, 'Business', '2026-06-17 17:09:53'),
(74, 'CRM', 72, 'Business', '2026-06-17 17:09:53'),
(75, 'Business Analysis', 78, 'Business', '2026-06-17 17:09:53'),
(76, 'Project Management', 80, 'Project Management', '2026-06-17 17:09:53'),
(77, 'Agile', 85, 'Project Management', '2026-06-17 17:09:53'),
(78, 'Scrum', 82, 'Project Management', '2026-06-17 17:09:53'),
(79, 'IT Support', 70, 'Support', '2026-06-17 17:09:53'),
(80, 'System Administration', 75, 'Support', '2026-06-17 17:09:53'),
(81, 'WordPress', 65, 'CMS', '2026-06-17 17:09:53'),
(82, 'SEO', 68, 'Digital Marketing', '2026-06-17 17:09:53'),
(83, 'Digital Marketing', 72, 'Digital Marketing', '2026-06-17 17:09:53'),
(84, 'IoT', 74, 'IoT', '2026-06-17 17:09:53'),
(85, 'Embedded Systems', 70, 'IoT', '2026-06-17 17:09:53'),
(86, 'Cloud Architecture', 85, 'Cloud', '2026-06-17 17:09:53'),
(87, 'Kotlin', 65, 'Programming', '2026-06-17 17:10:07'),
(88, 'Swift', 60, 'Programming', '2026-06-17 17:10:07'),
(89, 'PHP', 55, 'Programming', '2026-06-17 17:10:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Delpi', 'delpi@gmail.com', '$2y$10$hcw1mOusPveJL2PkJgUm7eplYyh1gRywnY2wignzt5XP0i7tmj0R6', '2026-06-18 16:43:14'),
(5, 'delpi', 'delpi12@gmail.com', '$2y$10$0Fu/epkXvEyaoXwwI9Ozv.2REm9pHpANIci64VGFNWNM/by91TIKe', '2026-06-18 17:50:09'),
(6, 'Nezza', 'nezza@gmail.com', '$2y$10$HTBQKuhqDz7q2IC48uNBb.E8DSuNtcQq6SaZmi6SMqgHL4dT3ZyoK', '2026-08-28 05:06:41'),
(7, 'Delpi', 'delpii@gmail.com', '$2y$10$VfSV1DV5VJphm/X1U99YCuMOvrpXwBdBnq4bzmk4h.HzNgkak3Jne', '2026-09-21 00:16:53'),
(8, 'Siti', 'siti@gmail.com', '$2y$10$Fxo7dJyOw5KlTxEHtwPRQuKaJM0PNepXjWEiCcxXjyXom5uAnYvsC', '2026-10-03 14:35:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_skills`
--

CREATE TABLE `user_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `proficiency_level` enum('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `skill_id`, `proficiency_level`, `description`, `created_at`) VALUES
(28, 5, 1, 'Beginner', '', '2026-06-20 16:37:03'),
(29, 5, 2, 'Beginner', '', '2026-06-20 16:37:03'),
(30, 5, 3, 'Beginner', '', '2026-06-20 16:37:03'),
(31, 5, 4, 'Beginner', '', '2026-06-20 16:37:03'),
(32, 5, 5, 'Advanced', '', '2026-06-20 16:37:03'),
(33, 5, 6, 'Beginner', '', '2026-06-20 16:37:03'),
(34, 5, 7, 'Intermediate', '', '2026-06-20 16:37:03'),
(35, 5, 8, 'Beginner', '', '2026-06-20 16:37:03'),
(36, 5, 9, 'Beginner', '', '2026-06-20 16:37:03'),
(37, 5, 10, 'Beginner', '', '2026-06-20 16:37:03'),
(38, 5, 11, 'Beginner', '', '2026-06-20 16:37:03'),
(39, 5, 12, 'Beginner', '', '2026-06-20 16:37:03'),
(40, 5, 13, 'Beginner', '', '2026-06-20 16:37:03'),
(41, 5, 14, 'Beginner', '', '2026-06-20 16:37:03'),
(42, 5, 15, 'Beginner', '', '2026-06-20 16:37:03'),
(43, 5, 16, 'Beginner', '', '2026-06-20 16:37:03'),
(44, 5, 17, 'Beginner', '', '2026-06-20 16:37:03'),
(45, 5, 18, 'Beginner', '', '2026-06-20 16:37:03'),
(46, 5, 19, 'Beginner', '', '2026-06-20 16:37:03');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `analysis_history`
--
ALTER TABLE `analysis_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `industry_skills`
--
ALTER TABLE `industry_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- Indeks untuk tabel `trending_jobs`
--
ALTER TABLE `trending_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_title` (`job_title`);

--
-- Indeks untuk tabel `trending_skills`
--
ALTER TABLE `trending_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `analysis_history`
--
ALTER TABLE `analysis_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `industry_skills`
--
ALTER TABLE `industry_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT untuk tabel `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `trending_jobs`
--
ALTER TABLE `trending_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `trending_skills`
--
ALTER TABLE `trending_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `analysis_history`
--
ALTER TABLE `analysis_history`
  ADD CONSTRAINT `analysis_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
