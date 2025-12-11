-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-12-2025 a las 21:04:48
-- Versión del servidor: 5.7.44
-- Versión de PHP: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cepeax8net_mallqui`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `credits` int(11) NOT NULL,
  `price_cents` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `code`, `name`, `credits`, `price_cents`, `image_path`) VALUES
(13, 'INFRA01', 'Infraestructura y Redes', 3, 6000, 'img/course-1.jpg'),
(14, 'DATAIA01', 'Ciencia de datos e IA', 3, 5500, 'img/course-2.jpg'),
(15, 'HERR01', 'Herramientas y metodologías', 2, 4700, 'img/course-3.jpg'),
(16, 'IA01', 'Inteligencia artificial (IA)', 3, 3300, 'img/course-4.jpg'),
(17, 'INFO01', 'Ciencia de Informacion', 2, 4500, 'img/course-5.jpg'),
(18, 'JS01', 'JavaScript', 2, 4800, 'img/course-6.jpg'),
(19, 'PY01', 'Python', 2, 3300, 'img/course-7.jpg'),
(20, 'ANAL01', 'Análisis de Datos', 4, 4500, 'img/course-8.jpg'),
(21, 'CHAT01', 'ChatGPT y Copilot', 1, 4500, 'img/course-9.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(10) UNSIGNED NOT NULL,
  `uploader_id` int(10) UNSIGNED DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `documents`
--

INSERT INTO `documents` (`id`, `payment_id`, `uploader_id`, `filename`, `mime_type`, `path`, `created_at`) VALUES
(1, 3, 12, 'plantilla_vales_A4.pdf', 'application/pdf', 'voucher_3_1762990762.pdf', '2025-11-12 18:39:23'),
(2, 5, 7, 'plantilla_vales_A4.pdf', 'application/pdf', 'voucher_5_1762992032.pdf', '2025-11-12 19:00:32'),
(3, 8, 18, 'SENDY MALLQUI .pdf', 'application/pdf', 'voucher_8_1763000338.pdf', '2025-11-12 21:18:58'),
(4, 17, 10, 'plantilla_vales_A4.pdf', 'application/pdf', 'voucher_17_1763602294.pdf', '2025-11-19 20:31:34'),
(5, 19, 10, 'plantilla_vales_A4.pdf', 'application/pdf', 'voucher_19_1763602825.pdf', '2025-11-19 20:40:25'),
(6, 21, 16, 'mark.jpg', 'image/jpeg', 'voucher_21_1763605658.jpg', '2025-11-19 21:27:38'),
(7, 23, 21, 'Pagina-web.jpg', 'image/jpeg', 'voucher_23_1763605831.jpg', '2025-11-19 21:30:31'),
(8, 34, 26, 'plantilla_vales_A4.pdf', 'application/pdf', 'voucher_34_1764207489.pdf', '2025-11-26 20:38:09'),
(9, 36, 28, 'Presentación Medio Ambiente Ilustrado Verde.pdf', 'application/pdf', 'voucher_36_1764208590.pdf', '2025-11-26 20:56:38'),
(10, 38, 26, 'plantilla_vales_A4.pdf', 'application/pdf', 'voucher_38_1764222096.pdf', '2025-11-27 00:41:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `status` enum('PENDING_PAYMENT','CONFIRMED','CANCELLED') NOT NULL DEFAULT 'PENDING_PAYMENT',
  `invoice_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `section_id`, `status`, `invoice_id`, `created_at`) VALUES
(16, 2, 21, 'PENDING_PAYMENT', 8, '2025-11-19 19:58:25'),
(17, 2, 22, 'PENDING_PAYMENT', 8, '2025-11-19 19:58:28'),
(18, 2, 16, 'PENDING_PAYMENT', 9, '2025-11-19 20:03:42'),
(22, 2, 19, 'PENDING_PAYMENT', 9, '2025-11-19 20:04:03'),
(23, 2, 20, 'PENDING_PAYMENT', 9, '2025-11-19 20:04:06'),
(27, 2, 14, 'PENDING_PAYMENT', 10, '2025-11-19 20:16:28'),
(28, 2, 18, 'PENDING_PAYMENT', 10, '2025-11-19 20:16:32'),
(32, 2, 15, 'PENDING_PAYMENT', 11, '2025-11-19 20:27:56'),
(35, 2, 17, 'PENDING_PAYMENT', 11, '2025-11-19 20:28:04'),
(36, 4, 21, 'PENDING_PAYMENT', 12, '2025-11-19 20:29:37'),
(37, 4, 19, 'PENDING_PAYMENT', 12, '2025-11-19 20:29:47'),
(38, 4, 20, 'PENDING_PAYMENT', 12, '2025-11-19 20:29:50'),
(39, 4, 16, 'PENDING_PAYMENT', 12, '2025-11-19 20:30:46'),
(40, 4, 18, 'PENDING_PAYMENT', 12, '2025-11-19 20:30:49'),
(41, 4, 14, 'PENDING_PAYMENT', 12, '2025-11-19 20:30:52'),
(45, 4, 15, 'PENDING_PAYMENT', 13, '2025-11-19 20:40:04'),
(47, 4, 22, 'PENDING_PAYMENT', 13, '2025-11-19 20:40:10'),
(54, 6, 19, 'PENDING_PAYMENT', 14, '2025-11-19 21:26:10'),
(55, 6, 17, 'PENDING_PAYMENT', 14, '2025-11-19 21:26:17'),
(56, 10, 18, 'PENDING_PAYMENT', 15, '2025-11-19 21:30:04'),
(57, 12, 22, 'PENDING_PAYMENT', 16, '2025-11-26 18:23:08'),
(58, 12, 15, 'PENDING_PAYMENT', NULL, '2025-11-26 18:40:18'),
(59, 12, 14, 'PENDING_PAYMENT', NULL, '2025-11-26 18:46:37'),
(72, 13, 21, 'PENDING_PAYMENT', 17, '2025-11-26 19:06:07'),
(73, 13, 17, 'PENDING_PAYMENT', 17, '2025-11-26 19:06:10'),
(74, 13, 20, 'PENDING_PAYMENT', 17, '2025-11-26 19:06:14'),
(75, 13, 18, 'PENDING_PAYMENT', 18, '2025-11-26 19:09:56'),
(77, 13, 19, 'PENDING_PAYMENT', 18, '2025-11-26 19:10:11'),
(80, 13, 16, 'PENDING_PAYMENT', 19, '2025-11-26 19:11:17'),
(81, 13, 15, 'PENDING_PAYMENT', 20, '2025-11-26 19:56:19'),
(85, 13, 22, 'PENDING_PAYMENT', 21, '2025-11-26 19:58:39'),
(98, 15, 15, 'PENDING_PAYMENT', 22, '2025-11-26 20:33:14'),
(99, 15, 16, 'PENDING_PAYMENT', 22, '2025-11-26 20:33:17'),
(100, 16, 21, 'PENDING_PAYMENT', NULL, '2025-11-26 20:45:06'),
(101, 16, 22, 'PENDING_PAYMENT', NULL, '2025-11-26 20:45:08'),
(102, 17, 15, 'PENDING_PAYMENT', 23, '2025-11-26 20:55:37'),
(103, 17, 18, 'PENDING_PAYMENT', 23, '2025-11-26 20:55:43'),
(105, 15, 20, 'PENDING_PAYMENT', 24, '2025-11-27 00:13:15'),
(106, 15, 18, 'PENDING_PAYMENT', 24, '2025-11-27 00:13:19'),
(109, 15, 19, 'PENDING_PAYMENT', NULL, '2025-11-27 00:41:57'),
(110, 15, 22, 'PENDING_PAYMENT', NULL, '2025-11-27 00:42:01'),
(111, 18, 21, 'PENDING_PAYMENT', NULL, '2025-11-27 22:22:58'),
(112, 18, 22, 'PENDING_PAYMENT', NULL, '2025-11-27 22:22:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(50) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `amount_cents` int(11) NOT NULL,
  `status` enum('OPEN','PAID','VOID') NOT NULL DEFAULT 'OPEN',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `invoices`
--

INSERT INTO `invoices` (`id`, `student_id`, `number`, `currency`, `amount_cents`, `status`, `created_at`) VALUES
(1, 2, 'INV-20251112-174612-5904', 'USD', 60000, 'OPEN', '2025-11-12 17:46:12'),
(2, 5, 'INV-20251112-182652-6726', 'USD', 60000, 'OPEN', '2025-11-12 18:26:52'),
(3, 2, 'INV-20251112-185816-7199', 'USD', 40000, 'OPEN', '2025-11-12 18:58:16'),
(4, 6, 'INV-20251112-194110-3957', 'USD', 70000, 'OPEN', '2025-11-12 19:41:10'),
(5, 7, 'INV-20251112-211759-3425', 'USD', 30000, 'OPEN', '2025-11-12 21:17:59'),
(6, 9, 'INV-20251119-193328-6635', 'USD', 70000, 'OPEN', '2025-11-19 19:33:28'),
(7, 2, 'INV-20251119-195523-9249', 'USD', 10000, 'OPEN', '2025-11-19 19:55:23'),
(8, 2, 'INV-20251119-195833-6260', 'USD', 30000, 'OPEN', '2025-11-19 19:58:33'),
(9, 2, 'INV-20251119-200513-7871', 'USD', 60000, 'OPEN', '2025-11-19 20:05:13'),
(10, 2, 'INV-20251119-201635-1284', 'USD', 50000, 'OPEN', '2025-11-19 20:16:35'),
(11, 2, 'INV-20251119-202807-8869', 'S/', 8800, 'OPEN', '2025-11-19 20:28:07'),
(12, 4, 'INV-20251119-203054-7988', 'S/', 28200, 'OPEN', '2025-11-19 20:30:54'),
(13, 4, 'INV-20251119-204012-8625', 'S/', 8500, 'OPEN', '2025-11-19 20:40:12'),
(14, 6, 'INV-20251119-212655-1637', 'S/', 8100, 'OPEN', '2025-11-19 21:26:55'),
(15, 10, 'INV-20251119-213017-8853', 'S/', 4600, 'OPEN', '2025-11-19 21:30:17'),
(16, 12, 'INV-20251126-182325-4056', 'S/', 3000, 'OPEN', '2025-11-26 18:23:25'),
(17, 13, 'INV-20251126-190709-1869', 'S/', 11100, 'OPEN', '2025-11-26 19:07:09'),
(18, 13, 'INV-20251126-191056-1487', 'S/', 9400, 'OPEN', '2025-11-26 19:10:56'),
(19, 13, 'INV-20251126-194817-6293', 'S/', 5000, 'OPEN', '2025-11-26 19:48:17'),
(20, 13, 'INV-20251126-195624-9460', 'S/', 5500, 'OPEN', '2025-11-26 19:56:24'),
(21, 13, 'INV-20251126-200143-6110', 'S/', 3000, 'OPEN', '2025-11-26 20:01:43'),
(22, 15, 'INV-20251126-203320-8332', 'S/', 10500, 'OPEN', '2025-11-26 20:33:20'),
(23, 17, 'INV-20251126-205555-4515', 'S/', 10100, 'OPEN', '2025-11-26 20:55:55'),
(24, 15, 'INV-20251127-004115-3307', 'S/', 7900, 'OPEN', '2025-11-27 00:41:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1',
  `unit_price_cents` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `qty`, `unit_price_cents`) VALUES
(1, 1, 'INF110 Programación I - Sec A1 (3 créditos)', 1, 30000),
(2, 1, 'ADM201 Contabilidad - Sec A1 (3 créditos)', 1, 30000),
(3, 2, 'INF110 Programación I - Sec A1 (3 créditos)', 1, 30000),
(4, 2, 'ADM201 Contabilidad - Sec A1 (3 créditos)', 1, 30000),
(5, 3, 'MAT101 Cálculo I - Sec A1 (4 créditos)', 1, 40000),
(6, 4, 'MAT101 Cálculo I - Sec B1 (4 créditos)', 1, 40000),
(7, 4, 'ADM201 Contabilidad - Sec A1 (3 créditos)', 1, 30000),
(8, 5, 'INF110 Programación I - Sec A1 (3 créditos)', 1, 30000),
(9, 6, 'MAT101 Cálculo I - Sec A1 (4 créditos)', 1, 40000),
(10, 6, 'ADM201 Contabilidad - Sec A1 (3 créditos)', 1, 30000),
(11, 7, 'CHAT01 ChatGPT y Copilot - Sec A1 (1 créditos)', 1, 10000),
(12, 8, 'ANAL01 Análisis de Datos - Sec A1 (2 créditos)', 1, 20000),
(13, 8, 'CHAT01 ChatGPT y Copilot - Sec A1 (1 créditos)', 1, 10000),
(14, 9, 'HERR01 Herramientas y metodologías - Sec A1 (2 créditos)', 1, 20000),
(15, 9, 'JS01 JavaScript - Sec A1 (2 créditos)', 1, 20000),
(16, 9, 'PY01 Python - Sec A1 (2 créditos)', 1, 20000),
(17, 10, 'INFRA01 Infraestructura y Redes - Sec A1 (3 créditos)', 1, 30000),
(18, 10, 'INFO01 Ciencia de Informacion - Sec A1 (2 créditos)', 1, 20000),
(19, 11, 'DATAIA01 Ciencia de datos e IA - Sec A1 (3 créditos)', 1, 5500),
(20, 11, 'IA01 Inteligencia artificial (IA) - Sec A1 (3 créditos)', 1, 3300),
(21, 12, 'INFRA01 Infraestructura y Redes - Sec A1 (3 créditos)', 1, 6000),
(22, 12, 'HERR01 Herramientas y metodologías - Sec A1 (2 créditos)', 1, 5000),
(23, 12, 'INFO01 Ciencia de Informacion - Sec A1 (2 créditos)', 1, 4600),
(24, 12, 'JS01 JavaScript - Sec A1 (2 créditos)', 1, 4800),
(25, 12, 'PY01 Python - Sec A1 (2 créditos)', 1, 3300),
(26, 12, 'ANAL01 Análisis de Datos - Sec A1 (2 créditos)', 1, 4500),
(27, 13, 'DATAIA01 Ciencia de datos e IA - Sec A1 (3 créditos)', 1, 5500),
(28, 13, 'CHAT01 ChatGPT y Copilot - Sec A1 (1 créditos)', 1, 3000),
(29, 14, 'IA01 Inteligencia artificial (IA) - Sec A1 (3 créditos)', 1, 3300),
(30, 14, 'JS01 JavaScript - Sec A1 (2 créditos)', 1, 4800),
(31, 15, 'INFO01 Ciencia de Informacion - Sec A1 (2 créditos)', 1, 4600),
(32, 16, 'CHAT01 ChatGPT y Copilot - Sec A1 (1 créditos)', 1, 3000),
(33, 17, 'IA01 Inteligencia artificial (IA) - Sec A1 (3 créditos)', 1, 3300),
(34, 17, 'PY01 Python - Sec A1 (2 créditos)', 1, 3300),
(35, 17, 'ANAL01 Análisis de Datos - Sec A1 (2 créditos)', 1, 4500),
(36, 18, 'INFO01 Ciencia de Informacion - Sec A1 (2 créditos)', 1, 4600),
(37, 18, 'JS01 JavaScript - Sec A1 (2 créditos)', 1, 4800),
(38, 19, 'HERR01 Herramientas y metodologías - Sec A1 (2 créditos)', 1, 5000),
(39, 20, 'DATAIA01 Ciencia de datos e IA - Sec A1 (3 créditos)', 1, 5500),
(40, 21, 'CHAT01 ChatGPT y Copilot - Sec A1 (1 créditos)', 1, 3000),
(41, 22, 'DATAIA01 Ciencia de datos e IA - Sec A1 (3 créditos)', 1, 5500),
(42, 22, 'HERR01 Herramientas y metodologías - Sec A1 (2 créditos)', 1, 5000),
(43, 23, 'DATAIA01 Ciencia de datos e IA - Sec A1 (3 créditos)', 1, 5500),
(44, 23, 'INFO01 Ciencia de Informacion - Sec A1 (2 créditos)', 1, 4600),
(45, 24, 'INFO01 Ciencia de Informacion - Sec A1 (2 créditos)', 1, 4600),
(46, 24, 'PY01 Python - Sec A1 (2 créditos)', 1, 3300);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `provider` varchar(50) NOT NULL,
  `provider_payment_id` varchar(100) DEFAULT NULL,
  `amount_cents` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `method` varchar(20) NOT NULL,
  `status` enum('PENDING','SUCCEEDED','FAILED','REVIEW','REFUNDED') NOT NULL DEFAULT 'PENDING',
  `verified_by` int(10) UNSIGNED DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `provider`, `provider_payment_id`, `amount_cents`, `currency`, `method`, `status`, `verified_by`, `verified_at`, `created_at`) VALUES
(1, 1, 'MOCK', NULL, 60000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-12 17:46:12'),
(2, 2, 'MOCK', NULL, 60000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-12 18:26:52'),
(3, 2, 'TRANSFER', NULL, 60000, 'USD', 'TRANSFER', 'FAILED', 15, '2025-11-12 19:32:12', '2025-11-12 18:38:16'),
(4, 3, 'MOCK', NULL, 40000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-12 18:58:16'),
(5, 3, 'TRANSFER', NULL, 40000, 'USD', 'TRANSFER', 'FAILED', 15, '2025-11-12 19:30:38', '2025-11-12 18:58:24'),
(6, 4, 'MOCK', NULL, 70000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-12 19:41:10'),
(7, 5, 'MOCK', NULL, 30000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-12 21:17:59'),
(8, 5, 'TRANSFER', NULL, 30000, 'USD', 'TRANSFER', 'FAILED', 15, '2025-11-12 21:23:32', '2025-11-12 21:18:35'),
(9, 6, 'MOCK', NULL, 70000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 19:33:28'),
(10, 6, 'TRANSFER', NULL, 70000, 'USD', 'TRANSFER', 'FAILED', 15, '2025-11-19 23:20:14', '2025-11-19 19:33:38'),
(11, 7, 'MOCK', NULL, 10000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 19:55:23'),
(12, 8, 'MOCK', NULL, 30000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 19:58:33'),
(13, 9, 'MOCK', NULL, 60000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 20:05:13'),
(14, 10, 'MOCK', NULL, 50000, 'USD', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 20:16:36'),
(15, 11, 'MOCK', NULL, 8800, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 20:28:07'),
(16, 12, 'MOCK', NULL, 28200, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 20:30:54'),
(17, 12, 'TRANSFER', NULL, 28200, 'S/', 'TRANSFER', 'FAILED', 15, '2025-11-19 23:21:08', '2025-11-19 20:31:23'),
(18, 13, 'MOCK', NULL, 8500, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 20:40:12'),
(19, 13, 'TRANSFER', NULL, 8500, 'S/', 'TRANSFER', 'FAILED', 15, '2025-12-03 20:29:41', '2025-11-19 20:40:17'),
(20, 14, 'MOCK', NULL, 8100, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 21:26:55'),
(21, 14, 'TRANSFER', NULL, 8100, 'S/', 'TRANSFER', 'FAILED', 15, '2025-11-26 18:51:02', '2025-11-19 21:27:26'),
(22, 15, 'MOCK', NULL, 4600, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-19 21:30:17'),
(23, 15, 'TRANSFER', NULL, 4600, 'S/', 'TRANSFER', 'FAILED', 15, '2025-11-26 18:51:00', '2025-11-19 21:30:23'),
(24, 16, 'MOCK', NULL, 3000, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 18:23:25'),
(25, 16, 'TRANSFER', NULL, 3000, 'S/', 'TRANSFER', 'FAILED', 15, '2025-11-26 18:50:57', '2025-11-26 18:24:37'),
(26, 11, 'TRANSFER', NULL, 8800, 'S/', 'TRANSFER', 'FAILED', 15, '2025-12-03 20:29:24', '2025-11-26 19:01:27'),
(27, 17, 'MOCK', NULL, 11100, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 19:07:09'),
(28, 18, 'MOCK', NULL, 9400, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 19:10:56'),
(29, 19, 'MOCK', NULL, 5000, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 19:48:17'),
(30, 20, 'MOCK', NULL, 5500, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 19:56:24'),
(31, 21, 'MOCK', NULL, 3000, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 20:01:43'),
(32, 9, 'TRANSFER', NULL, 60000, 'USD', 'TRANSFER', 'REVIEW', NULL, NULL, '2025-11-26 20:27:24'),
(33, 22, 'MOCK', NULL, 10500, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 20:33:20'),
(34, 22, 'TRANSFER', NULL, 10500, 'S/', 'TRANSFER', 'REVIEW', NULL, NULL, '2025-11-26 20:33:28'),
(35, 23, 'MOCK', NULL, 10100, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-26 20:55:55'),
(36, 23, 'TRANSFER', NULL, 10100, 'S/', 'TRANSFER', 'FAILED', 15, '2025-12-03 20:29:27', '2025-11-26 20:56:08'),
(37, 24, 'MOCK', NULL, 7900, 'S/', 'CARD', 'PENDING', NULL, NULL, '2025-11-27 00:41:15'),
(38, 24, 'TRANSFER', NULL, 7900, 'S/', 'TRANSFER', 'FAILED', 15, '2025-12-03 20:29:22', '2025-11-27 00:41:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sections`
--

CREATE TABLE `sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `weekday` tinyint(4) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT '30',
  `enrolled_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sections`
--

INSERT INTO `sections` (`id`, `course_id`, `code`, `weekday`, `start_time`, `end_time`, `capacity`, `enrolled_count`) VALUES
(14, 13, 'A1', 1, '18:00:00', '20:00:00', 60, 0),
(15, 14, 'A1', 2, '18:00:00', '20:00:00', 30, 0),
(16, 15, 'A1', 3, '18:00:00', '20:00:00', 20, 0),
(17, 16, 'A1', 4, '18:00:00', '20:00:00', 50, 0),
(18, 17, 'A1', 5, '18:00:00', '20:00:00', 20, 0),
(19, 18, 'A1', 1, '20:00:00', '22:00:00', 20, 0),
(20, 19, 'A1', 2, '20:00:00', '22:00:00', 70, 0),
(21, 20, 'A1', 3, '20:00:00', '22:00:00', 40, 0),
(22, 21, 'A1', 4, '20:00:00', '22:00:00', 20, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `doc_id` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `user_id`, `full_name`, `doc_id`, `created_at`) VALUES
(1, 4, 'Fernando', '74859674', '2025-11-12 17:36:56'),
(2, 7, 'Fernan', '77485965', '2025-11-12 17:44:23'),
(3, 8, 'Juana', '76958748', '2025-11-12 18:09:04'),
(4, 10, 'Tamara', '71452874', '2025-11-12 18:11:01'),
(5, 12, 'Gabriel', '78547859', '2025-11-12 18:25:55'),
(6, 16, 'karina mendoza carrion', '10460502', '2025-11-12 19:40:11'),
(7, 18, 'karina mendoza carrion', '10460502', '2025-11-12 21:17:08'),
(8, 19, 'mercedes', '7733900987', '2025-11-18 21:58:06'),
(9, 20, 'GILBERTO', 'QUETI', '2025-11-19 19:23:13'),
(10, 21, 'JOSE PEREZ', '76544445', '2025-11-19 21:29:26'),
(11, 22, 'qwertyuytr876543', 'ghjkjhgfd', '2025-11-19 23:16:24'),
(12, 23, 'cecilia prado', '76543218', '2025-11-26 18:14:17'),
(13, 24, 'Hugo', '78574859', '2025-11-26 19:05:25'),
(14, 25, 'lola', '73345677', '2025-11-26 20:30:46'),
(15, 26, 'Jimena', '74859612', '2025-11-26 20:32:45'),
(16, 27, 'Kurt', '75418596', '2025-11-26 20:44:27'),
(17, 28, 'cesar padilla', '73378965', '2025-11-26 20:54:55'),
(18, 29, 'keyla', '78456555', '2025-11-27 22:22:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('STUDENT','ADMIN') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role`, `created_at`) VALUES
(4, 'fernan@gmail.com', '$2y$10$1h9z0Nee4WQih8xLN1dE5OiCgyEbuzBwxnGrq4l97Zs6oS32pXnvG', 'STUDENT', '2025-11-12 17:36:56'),
(7, 'fernan74@gmail.com', '$2y$10$NwG2ovAL.2WWwqfHRtpExeE/lOEnDOElDSQfpWkgs/2L7k3sZJze6', 'STUDENT', '2025-11-12 17:44:23'),
(8, 'juana478@gmail.com', '$2y$10$yxFrzeU430qEzh.dCwG8ceVVy9aJmbKeQ56gZfvFGYO8DRLBwvJBG', 'STUDENT', '2025-11-12 18:09:04'),
(10, 'tamara@gmail.com', '$2y$10$Kifn8cPue9WByclntHixVOAID8HtoJdD18qyVNPaQvbqDdpMP1tCa', 'STUDENT', '2025-11-12 18:11:01'),
(12, 'Gab785@gmail.com', '$2y$10$xIE66amirAmkQZCtwwRNB.mI/ThHEm2YuQJWGDWgI54CxKclblsPy', 'STUDENT', '2025-11-12 18:25:55'),
(15, 'admin@example.com', '$2y$10$headdG7YcedzXuHmyeUYO.TXdwiL2WTvp8l2e6rD2ARJUaVogc2DG', 'ADMIN', '2025-11-12 19:10:24'),
(16, 'sancristobaldemanzanayocc@hotmail.com', '$2y$10$iiER/qnxYTE5gfbx8JZGBe8D58Cdf2NcvB8dCB3DZgUIbi2UsPfr6', 'STUDENT', '2025-11-12 19:40:11'),
(18, 'sendylizana@hotmail.com', '$2y$10$1AP/l3IwkWtK2fpGs5pQH.8OkojePialjmIeG3WwkFG.g/7PuFLhi', 'STUDENT', '2025-11-12 21:17:08'),
(19, 'sendycepea23@gmail.com', '$2y$10$nVkfhmTMr2FgVMLuAEg15uxquFXOe8oApLsoD0zb/fPKFhGkLA1bC', 'STUDENT', '2025-11-18 21:58:06'),
(20, 'Jiminpremium2025@gmail.com', '$2y$10$NvQtXnybtZRR00IfP0isM.dGBm4iXvWfjfz1V7mZipEZ7408c99eC', 'STUDENT', '2025-11-19 19:23:13'),
(21, 'jose@hotmail.com', '$2y$10$PIWSbHSBzpgbbl0V2uMzhOeDHpra2hVw7Ftwox1RxMsgqV1NFnE8i', 'STUDENT', '2025-11-19 21:29:26'),
(22, 'curso@gmail.com', '$2y$10$4TSUbewfo0PlNCuExwGn0u9QY4biGKPeDD0h1RKRffQwTkhBSFf8y', 'STUDENT', '2025-11-19 23:16:24'),
(23, 'ceciliaprado@gmail.com', '$2y$10$krFWu6ABD9WU5te3VSVrFuTW7IB6NVA9IJ90A7AM5VcnepfqE0E/6', 'STUDENT', '2025-11-26 18:14:17'),
(24, 'thiago@gmail.com', '$2y$10$KGFzlwikpd.9KWwStg8UaOXstS4opHVyuMprV0oWVqfBAbnvU.ASq', 'STUDENT', '2025-11-26 19:05:25'),
(25, 'lola@hotmail.com', '$2y$10$LRWOchY1PUZAVzXWTdyJo.SQBIHlpFE0OVJXVLO203gluZMk1QYLi', 'STUDENT', '2025-11-26 20:30:46'),
(26, 'jimena12@gmail.com', '$2y$10$PEuId7rlCKY0eC.lSa86P.fR47RFaIHyq1dx0ERUQsPZP1iX5fOGe', 'STUDENT', '2025-11-26 20:32:45'),
(27, 'kurt@gmail.com', '$2y$10$4CQnVJvwOuqYqcvOFwA2ceK0cC3CWT.q4tCEgnzqC/21vVJShyGfC', 'STUDENT', '2025-11-26 20:44:27'),
(28, 'cesar@hotmail.com', '$2y$10$z2YoIke3l5jAjEjRnYq5jehiMqMA0f5QiTZCDx9ayJ2rapJdS1XYy', 'STUDENT', '2025-11-26 20:54:55'),
(29, 'keyla@gmail.com', '$2y$10$Xy87zoUU908hk5fjPnXPKOqMi7MbrAb/ICysftKc2LyGPfbOvLtry', 'STUDENT', '2025-11-27 22:22:34');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_docs_payment` (`payment_id`),
  ADD KEY `fk_docs_uploader` (`uploader_id`);

--
-- Indices de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_section` (`student_id`,`section_id`),
  ADD KEY `fk_enr_section` (`section_id`),
  ADD KEY `fk_enr_invoice` (`invoice_id`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `fk_invoices_student` (`student_id`);

--
-- Indices de la tabla `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inv_items_invoice` (`invoice_id`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_invoice` (`invoice_id`),
  ADD KEY `fk_payments_verified_by` (`verified_by`);

--
-- Indices de la tabla `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_course_section` (`course_id`,`code`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_docs_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_docs_uploader` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enr_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `fk_enr_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enr_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_inv_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_payments_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_sections_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
