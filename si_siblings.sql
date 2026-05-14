-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 14, 2026 at 07:21 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_siblings`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--
-- Creation: May 14, 2026 at 06:57 AM
-- Last update: May 14, 2026 at 06:57 AM
--

CREATE TABLE `customers` (
  `customer_id` int NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `customers`:
--

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `nama`, `no_hp`, `catatan`) VALUES
(1, 'Customer 01', '0801', 'CUST01'),
(2, 'Customer 02', '0802', 'CUST02'),
(3, 'Customer 03', '0803', 'CUST03'),
(4, 'Customer 04', '0804', 'CUST04'),
(5, 'Customer 06', '0805', 'CUST05');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--
-- Creation: May 14, 2026 at 06:57 AM
-- Last update: May 14, 2026 at 07:16 AM
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `order_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int DEFAULT NULL,
  `tanggal_order` datetime NOT NULL,
  `status_order` enum('dp_50_selesai','sedang_diproses','siap_diambil','lunas_belum_diambil','lunas_sudah_diambil') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `total_qty` int NOT NULL DEFAULT '0',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_addon` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `orders`:
--   `customer_id`
--       `customers` -> `customer_id`
--   `user_id`
--       `users` -> `user_id`
--

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_code`, `customer_id`, `tanggal_order`, `status_order`, `catatan`, `total_qty`, `subtotal`, `total_addon`, `grand_total`, `user_id`) VALUES
(1, 'ORD-20260315-001', 1, '2026-03-15 08:30:00', 'lunas_sudah_diambil', 'Jersey futsal turnamen April', 84, 8028000.00, 295000.00, 8323000.00, 2),
(2, 'ORD-20260330-002', 2, '2026-03-30 09:45:00', 'lunas_belum_diambil', 'Kaos acara kampus BEM', 72, 11280000.00, 650000.00, 11930000.00, 2),
(3, 'ORD-20260428-003', 1, '2026-04-28 10:30:00', 'lunas_belum_diambil', 'PDH seragam organisasi', 120, 15000000.00, 110000.00, 15110000.00, 2),
(4, 'ORD-20260507-004', 4, '2026-05-07 08:30:00', 'sedang_diproses', 'Jersey premium set lengkap', 60, 7920000.00, 0.00, 7920000.00, 2),
(5, 'ORD-20260417-005', 1, '2026-04-17 16:45:00', 'siap_diambil', 'Polo shirt seragam panitia', 24, 3600000.00, 120000.00, 3720000.00, 2),
(6, 'ORD-20260328-006', 1, '2026-03-28 10:45:00', 'lunas_sudah_diambil', 'Jacket hoodie custom komunitas', 24, 3120000.00, 176000.00, 3296000.00, 2),
(7, 'ORD-20260508-007', 2, '2026-05-08 16:15:00', 'dp_50_selesai', 'Seragam olahraga set lengkap', 60, 5460000.00, 430000.00, 5890000.00, 2),
(8, 'ORD-20260308-008', 3, '2026-03-08 08:45:00', 'lunas_sudah_diambil', 'T-Shirt combed wisuda', 84, 8472000.00, 0.00, 8472000.00, 2),
(9, 'ORD-20260309-009', 2, '2026-03-09 11:30:00', 'lunas_sudah_diambil', 'Jersey jaquard full print', 132, 13740000.00, 175000.00, 13915000.00, 2),
(10, 'ORD-20260510-010', 5, '2026-05-10 10:45:00', 'dp_50_selesai', 'PDH American Drill bordir', 48, 5400000.00, 0.00, 5400000.00, 2),
(11, 'ORD-20260401-011', 3, '2026-04-01 13:15:00', 'lunas_sudah_diambil', 'Order jersey rutin mei', 24, 2040000.00, 50000.00, 2090000.00, 2),
(12, 'ORD-20260505-012', 1, '2026-05-05 15:30:00', 'dp_50_selesai', 'Kaos gathering perusahaan', 24, 3240000.00, 0.00, 3240000.00, 2),
(13, 'ORD-20260325-013', 3, '2026-03-25 13:00:00', 'lunas_sudah_diambil', 'Seragam olahraga ekskul', 96, 12840000.00, 520000.00, 13360000.00, 2),
(14, 'ORD-20260319-014', 1, '2026-03-19 08:00:00', 'lunas_belum_diambil', 'Jersey bola antar RT', 30, 2550000.00, 25000.00, 2575000.00, 2),
(15, 'ORD-20260511-015', 2, '2026-05-11 11:00:00', 'dp_50_selesai', 'PDH pengurus baru', 96, 10860000.00, 115000.00, 10975000.00, 2),
(16, 'ORD-20260327-016', 3, '2026-03-27 15:45:00', 'lunas_belum_diambil', 'Polo shirt reuni alumni', 60, 4020000.00, 195000.00, 4215000.00, 2),
(17, 'ORD-20260411-017', 5, '2026-04-11 11:45:00', 'lunas_sudah_diambil', 'Hoodie angkatan 2024', 48, 5520000.00, 240000.00, 5760000.00, 2),
(18, 'ORD-20260322-018', 2, '2026-03-22 09:15:00', 'lunas_belum_diambil', 'T-shirt ulang tahun komunitas', 36, 5400000.00, 0.00, 5400000.00, 2),
(19, 'ORD-20260316-019', 4, '2026-03-16 08:30:00', 'lunas_belum_diambil', 'Jersey turnamen futsal Juni', 78, 5826000.00, 0.00, 5826000.00, 2),
(20, 'ORD-20260501-020', 3, '2026-05-01 16:45:00', 'dp_50_selesai', 'Seragam olahraga sekolah', 24, 1488000.00, 0.00, 1488000.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--
-- Creation: May 14, 2026 at 06:57 AM
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `desain_referensi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `catatan_item` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `order_items`:
--   `order_id`
--       `orders` -> `order_id`
--   `variant_id`
--       `product_variants` -> `variant_id`
--

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `variant_id`, `desain_referensi`, `qty`, `harga_satuan`, `subtotal`, `catatan_item`) VALUES
(1, 1, 4, 'desain_order01_item1.jpg', 24, 130000.00, 3120000.00, 'Tolong sablon bagus ya'),
(2, 1, 3, 'desain_order01_item2.jpg', 24, 62000.00, 1488000.00, NULL),
(3, 1, 14, 'desain_order01_item3.jpg', 36, 95000.00, 3420000.00, 'Urgent sebelum event'),
(4, 2, 6, 'desain_order02_item4.jpg', 24, 140000.00, 3360000.00, 'Desain sudah dikirim via WA'),
(5, 2, 12, 'desain_order02_item5.jpg', 48, 165000.00, 7920000.00, NULL),
(6, 3, 14, 'desain_order03_item6.jpg', 24, 95000.00, 2280000.00, 'Warna navy gelap'),
(7, 3, 13, 'desain_order03_item7.jpg', 48, 100000.00, 4800000.00, 'Pesanan rutin bulanan'),
(8, 3, 12, 'desain_order03_item8.jpg', 48, 165000.00, 7920000.00, 'Minta jahitan rapi'),
(9, 4, 10, 'desain_order04_item9.jpg', 24, 150000.00, 3600000.00, 'Desain sudah dikirim via WA'),
(10, 4, 8, 'desain_order04_item10.jpg', 36, 120000.00, 4320000.00, 'Warna navy gelap'),
(11, 5, 11, 'desain_order05_item11.jpg', 24, 150000.00, 3600000.00, 'Bonus sesuai kesepakatan'),
(12, 6, 4, 'desain_order06_item12.jpg', 24, 130000.00, 3120000.00, NULL),
(13, 7, 8, 'desain_order07_item13.jpg', 30, 120000.00, 3600000.00, NULL),
(14, 7, 3, 'desain_order07_item14.jpg', 30, 62000.00, 1860000.00, 'Minta jahitan rapi'),
(15, 8, 3, 'desain_order08_item15.jpg', 36, 62000.00, 2232000.00, 'Urgent sebelum event'),
(16, 8, 6, 'desain_order08_item16.jpg', 24, 140000.00, 3360000.00, NULL),
(17, 8, 18, 'desain_order08_item17.jpg', 24, 120000.00, 2880000.00, 'Tolong sablon bagus ya'),
(18, 9, 10, 'desain_order09_item18.jpg', 48, 150000.00, 7200000.00, 'Pesanan rutin bulanan'),
(19, 9, 17, 'desain_order09_item19.jpg', 48, 95000.00, 4560000.00, NULL),
(20, 9, 1, 'desain_order09_item20.jpg', 36, 55000.00, 1980000.00, NULL),
(21, 10, 2, 'desain_order10_item21.jpg', 24, 60000.00, 1440000.00, NULL),
(22, 10, 12, 'desain_order10_item22.jpg', 24, 165000.00, 3960000.00, 'Warna navy gelap'),
(23, 11, 16, 'desain_order11_item23.jpg', 24, 85000.00, 2040000.00, 'Desain sudah dikirim via WA'),
(24, 12, 9, 'desain_order12_item24.jpg', 24, 135000.00, 3240000.00, 'Urgent sebelum event'),
(25, 13, 18, 'desain_order13_item25.jpg', 30, 120000.00, 3600000.00, 'Urgent sebelum event'),
(26, 13, 7, 'desain_order13_item26.jpg', 30, 110000.00, 3300000.00, NULL),
(27, 13, 12, 'desain_order13_item27.jpg', 36, 165000.00, 5940000.00, 'Full set termasuk kaos kaki'),
(28, 14, 15, 'desain_order14_item28.jpg', 30, 85000.00, 2550000.00, NULL),
(29, 15, 17, 'desain_order15_item29.jpg', 36, 95000.00, 3420000.00, NULL),
(30, 15, 11, 'desain_order15_item30.jpg', 36, 150000.00, 5400000.00, 'Full set termasuk kaos kaki'),
(31, 15, 15, 'desain_order15_item31.jpg', 24, 85000.00, 2040000.00, 'Warna navy gelap'),
(32, 16, 16, 'desain_order16_item32.jpg', 24, 85000.00, 2040000.00, 'Minta jahitan rapi'),
(33, 16, 1, 'desain_order16_item33.jpg', 36, 55000.00, 1980000.00, 'Pesanan rutin bulanan'),
(34, 17, 14, 'desain_order17_item34.jpg', 24, 95000.00, 2280000.00, 'Full set termasuk kaos kaki'),
(35, 17, 9, 'desain_order17_item35.jpg', 24, 135000.00, 3240000.00, 'Tolong sablon bagus ya'),
(36, 18, 10, 'desain_order18_item36.jpg', 36, 150000.00, 5400000.00, NULL),
(37, 19, 3, 'desain_order19_item37.jpg', 48, 62000.00, 2976000.00, 'Ada logo depan + belakang'),
(38, 19, 17, 'desain_order19_item38.jpg', 30, 95000.00, 2850000.00, NULL),
(39, 20, 3, 'desain_order20_item39.jpg', 24, 62000.00, 1488000.00, 'Full set termasuk kaos kaki');

-- --------------------------------------------------------

--
-- Table structure for table `order_item_addons`
--
-- Creation: May 14, 2026 at 06:57 AM
--

CREATE TABLE `order_item_addons` (
  `order_item_addon_id` int NOT NULL,
  `order_item_id` int NOT NULL,
  `addon_id` int NOT NULL,
  `qty` int NOT NULL,
  `biaya_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `order_item_addons`:
--   `addon_id`
--       `product_addons` -> `addon_id`
--   `order_item_id`
--       `order_items` -> `order_item_id`
--

--
-- Dumping data for table `order_item_addons`
--

INSERT INTO `order_item_addons` (`order_item_addon_id`, `order_item_id`, `addon_id`, `qty`, `biaya_satuan`, `subtotal`) VALUES
(1, 1, 3, 7, 10000.00, 70000.00),
(2, 1, 4, 9, 12000.00, 108000.00),
(3, 2, 1, 7, 5000.00, 35000.00),
(4, 3, 7, 7, 10000.00, 70000.00),
(5, 4, 4, 8, 12000.00, 96000.00),
(6, 5, 7, 10, 10000.00, 100000.00),
(7, 5, 8, 5, 10000.00, 50000.00),
(8, 6, 4, 8, 12000.00, 96000.00),
(9, 6, 3, 10, 10000.00, 100000.00),
(10, 7, 8, 8, 10000.00, 80000.00),
(11, 7, 6, 7, 45000.00, 315000.00),
(12, 8, 1, 6, 5000.00, 30000.00),
(13, 9, 6, 5, 45000.00, 225000.00),
(14, 11, 5, 10, 5000.00, 50000.00),
(15, 12, 3, 9, 10000.00, 90000.00),
(16, 13, 6, 8, 45000.00, 360000.00),
(17, 13, 8, 8, 10000.00, 80000.00),
(18, 14, 2, 5, 5000.00, 25000.00),
(19, 16, 3, 7, 10000.00, 70000.00),
(20, 16, 4, 9, 12000.00, 108000.00),
(21, 17, 7, 10, 10000.00, 100000.00),
(22, 17, 5, 5, 30000.00, 150000.00),
(23, 19, 1, 7, 5000.00, 35000.00),
(24, 22, 7, 5, 10000.00, 50000.00),
(25, 26, 7, 10, 10000.00, 100000.00),
(26, 27, 5, 8, 30000.00, 240000.00),
(27, 29, 1, 9, 5000.00, 45000.00),
(28, 30, 6, 7, 45000.00, 315000.00),
(29, 31, 2, 8, 5000.00, 40000.00),
(30, 35, 5, 8, 30000.00, 240000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_item_sizes`
--
-- Creation: May 14, 2026 at 06:57 AM
--

CREATE TABLE `order_item_sizes` (
  `order_item_size_id` int NOT NULL,
  `order_item_id` int NOT NULL,
  `ukuran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `order_item_sizes`:
--   `order_item_id`
--       `order_items` -> `order_item_id`
--

--
-- Dumping data for table `order_item_sizes`
--

INSERT INTO `order_item_sizes` (`order_item_size_id`, `order_item_id`, `ukuran`, `qty`) VALUES
(1, 1, 'M', 2),
(2, 1, 'XXL', 19),
(3, 1, 'L', 3),
(4, 2, 'L', 12),
(5, 2, 'XXL', 2),
(6, 2, 'S', 2),
(7, 2, 'XL', 8),
(8, 3, 'S', 20),
(9, 3, 'L', 5),
(10, 3, 'XL', 3),
(11, 3, 'M', 8),
(12, 4, 'L', 4),
(13, 4, 'S', 9),
(14, 4, 'XXL', 5),
(15, 4, 'XL', 3),
(16, 4, 'M', 3),
(17, 5, 'M', 22),
(18, 5, 'L', 14),
(19, 5, 'S', 4),
(20, 5, 'XL', 2),
(21, 5, 'XXL', 6),
(22, 6, 'XL', 12),
(23, 6, 'S', 3),
(24, 6, 'XXL', 5),
(25, 6, 'L', 2),
(26, 6, 'M', 2),
(27, 7, 'L', 12),
(28, 7, 'S', 16),
(29, 7, 'M', 2),
(30, 7, 'XXL', 18),
(31, 7, 'XL', 2),
(32, 8, 'M', 11),
(33, 8, 'S', 13),
(34, 8, 'L', 7),
(35, 8, 'XXL', 10),
(36, 8, 'XL', 7),
(37, 9, 'S', 14),
(38, 9, 'XXL', 4),
(39, 9, 'L', 2),
(40, 9, 'M', 2),
(41, 9, 'XL', 2),
(42, 10, 'XXL', 24),
(43, 10, 'XL', 4),
(44, 10, 'S', 5),
(45, 10, 'L', 3),
(46, 11, 'M', 5),
(47, 11, 'XXL', 3),
(48, 11, 'S', 2),
(49, 11, 'L', 7),
(50, 11, 'XL', 7),
(51, 12, 'L', 3),
(52, 12, 'XL', 5),
(53, 12, 'M', 2),
(54, 12, 'XXL', 14),
(55, 13, 'S', 9),
(56, 13, 'XXL', 4),
(57, 13, 'L', 8),
(58, 13, 'XL', 5),
(59, 13, 'M', 4),
(60, 14, 'L', 6),
(61, 14, 'XL', 8),
(62, 14, 'XXL', 6),
(63, 14, 'M', 10),
(64, 15, 'M', 21),
(65, 15, 'XL', 3),
(66, 15, 'S', 5),
(67, 15, 'L', 4),
(68, 15, 'XXL', 3),
(69, 16, 'L', 12),
(70, 16, 'M', 4),
(71, 16, 'XL', 3),
(72, 16, 'XXL', 3),
(73, 16, 'S', 2),
(74, 17, 'XXL', 18),
(75, 17, 'S', 2),
(76, 17, 'XL', 2),
(77, 17, 'L', 2),
(78, 18, 'S', 8),
(79, 18, 'M', 11),
(80, 18, 'XL', 10),
(81, 18, 'XXL', 19),
(82, 19, 'XXL', 7),
(83, 19, 'XL', 29),
(84, 19, 'M', 6),
(85, 19, 'S', 6),
(86, 20, 'XXL', 30),
(87, 20, 'S', 2),
(88, 20, 'XL', 2),
(89, 20, 'L', 2),
(90, 21, 'S', 15),
(91, 21, 'L', 6),
(92, 21, 'XL', 3),
(93, 22, 'XL', 12),
(94, 22, 'S', 8),
(95, 22, 'XXL', 4),
(96, 23, 'L', 2),
(97, 23, 'XXL', 8),
(98, 23, 'S', 8),
(99, 23, 'XL', 6),
(100, 24, 'XL', 11),
(101, 24, 'L', 6),
(102, 24, 'XXL', 2),
(103, 24, 'M', 3),
(104, 24, 'S', 2),
(105, 25, 'L', 18),
(106, 25, 'XXL', 4),
(107, 25, 'XL', 4),
(108, 25, 'S', 2),
(109, 25, 'M', 2),
(110, 26, 'M', 11),
(111, 26, 'L', 6),
(112, 26, 'XXL', 5),
(113, 26, 'S', 8),
(114, 27, 'M', 28),
(115, 27, 'S', 2),
(116, 27, 'XXL', 2),
(117, 27, 'L', 2),
(118, 27, 'XL', 2),
(119, 28, 'M', 6),
(120, 28, 'XL', 2),
(121, 28, 'XXL', 5),
(122, 28, 'L', 14),
(123, 28, 'S', 3),
(124, 29, 'XL', 10),
(125, 29, 'XXL', 9),
(126, 29, 'S', 12),
(127, 29, 'M', 3),
(128, 29, 'L', 2),
(129, 30, 'M', 9),
(130, 30, 'L', 10),
(131, 30, 'XXL', 7),
(132, 30, 'S', 4),
(133, 30, 'XL', 6),
(134, 31, 'M', 6),
(135, 31, 'XL', 13),
(136, 31, 'L', 5),
(137, 32, 'XL', 10),
(138, 32, 'M', 8),
(139, 32, 'XXL', 3),
(140, 32, 'S', 3),
(141, 33, 'XL', 22),
(142, 33, 'S', 6),
(143, 33, 'L', 5),
(144, 33, 'M', 3),
(145, 34, 'L', 2),
(146, 34, 'XL', 13),
(147, 34, 'S', 3),
(148, 34, 'M', 6),
(149, 35, 'M', 10),
(150, 35, 'L', 8),
(151, 35, 'XXL', 4),
(152, 35, 'S', 2),
(153, 36, 'L', 9),
(154, 36, 'XXL', 8),
(155, 36, 'M', 10),
(156, 36, 'S', 9),
(157, 37, 'S', 10),
(158, 37, 'M', 20),
(159, 37, 'XXL', 6),
(160, 37, 'XL', 8),
(161, 37, 'L', 4),
(162, 38, 'M', 12),
(163, 38, 'XL', 10),
(164, 38, 'L', 5),
(165, 38, 'S', 3),
(166, 39, 'L', 8),
(167, 39, 'S', 8),
(168, 39, 'XL', 5),
(169, 39, 'M', 3);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--
-- Creation: May 14, 2026 at 06:57 AM
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `metode_bayar` enum('cash','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_bayar` decimal(12,2) NOT NULL,
  `status_bayar` enum('pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `payments`:
--   `order_id`
--       `orders` -> `order_id`
--

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `tanggal_bayar`, `metode_bayar`, `jumlah_bayar`, `status_bayar`, `keterangan`) VALUES
(1, 1, '2026-03-16 08:30:00', 'transfer', 4161500.00, 'paid', 'DP 50%'),
(2, 1, '2026-03-29 08:30:00', 'cash', 4161500.00, 'paid', 'Pelunasan'),
(3, 2, '2026-03-31 09:45:00', 'transfer', 5965000.00, 'paid', 'DP 50%'),
(4, 2, '2026-04-14 09:45:00', 'cash', 5965000.00, 'paid', 'Pelunasan'),
(5, 3, '2026-04-29 10:30:00', 'transfer', 7555000.00, 'paid', 'Lunas penuh'),
(6, 4, '2026-05-08 08:30:00', 'transfer', 3960000.00, 'paid', 'DP 50%'),
(7, 5, '2026-04-18 16:45:00', 'cash', 1860000.00, 'paid', 'DP 50%'),
(8, 6, '2026-03-29 10:45:00', 'cash', 1648000.00, 'paid', 'DP 50%'),
(9, 6, '2026-04-10 10:45:00', 'transfer', 1648000.00, 'paid', 'Pelunasan'),
(10, 7, '2026-05-09 16:15:00', 'cash', 2945000.00, 'paid', 'DP 50%'),
(11, 8, '2026-03-09 08:45:00', 'transfer', 4236000.00, 'paid', 'Lunas penuh'),
(12, 9, '2026-03-10 11:30:00', 'transfer', 6957500.00, 'paid', 'DP 50%'),
(13, 9, '2026-03-24 11:30:00', 'cash', 6957500.00, 'paid', 'Pelunasan'),
(14, 10, '2026-05-11 10:45:00', 'cash', 2700000.00, 'paid', 'DP 50%'),
(15, 11, '2026-04-02 13:15:00', 'transfer', 1045000.00, 'paid', 'DP 50%'),
(16, 11, '2026-04-16 13:15:00', 'transfer', 1045000.00, 'paid', 'Pelunasan'),
(17, 12, '2026-05-06 15:30:00', 'cash', 1620000.00, 'paid', 'DP 50%'),
(18, 13, '2026-03-26 13:00:00', 'transfer', 6680000.00, 'paid', 'DP 50%'),
(19, 13, '2026-04-09 13:00:00', 'cash', 6680000.00, 'paid', 'Pelunasan'),
(20, 14, '2026-03-20 08:00:00', 'transfer', 1287500.00, 'paid', 'Lunas penuh'),
(21, 15, '2026-05-12 11:00:00', 'transfer', 5487500.00, 'paid', 'DP 50%'),
(22, 16, '2026-03-28 15:45:00', 'cash', 2107500.00, 'paid', 'DP 50%'),
(23, 16, '2026-04-11 15:45:00', 'cash', 2107500.00, 'paid', 'Pelunasan'),
(24, 17, '2026-04-12 11:45:00', 'cash', 2880000.00, 'paid', 'DP 50%'),
(25, 17, '2026-04-26 11:45:00', 'transfer', 2880000.00, 'paid', 'Pelunasan'),
(26, 18, '2026-03-23 09:15:00', 'transfer', 2700000.00, 'paid', 'Lunas penuh'),
(27, 19, '2026-03-17 08:30:00', 'cash', 2913000.00, 'paid', 'DP 50%'),
(28, 20, '2026-05-01 18:45:00', 'transfer', 744000.00, 'pending', 'Menunggu konfirmasi transfer');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
-- Creation: May 14, 2026 at 06:57 AM
-- Last update: May 14, 2026 at 06:57 AM
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  `nama_produk` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `minimal_order` int DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `products`:
--   `category_id`
--       `product_categories` -> `category_id`
--
--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `nama_produk`, `deskripsi`, `minimal_order`, `aktif`) VALUES
(1, 1, 'T-Shirt', 'Produk tshirt berbagai bahan & sablon', 24, 1),
(2, 2, 'PDH / Workshirt', 'PDH dengan beberapa pilihan kain & bordir 3 titik', 24, 1),
(3, 3, 'Jersey', 'Jersey print, full print, premium, jacquard, dll.', 24, 1),
(4, 4, 'Polo Shirt', 'Polo shirt bahan premium cotton 24s / lacos 24s', 24, 1),
(5, 5, 'Seragam Olahraga', 'Set seragam olahraga baju + training', 24, 1),
(6, 6, 'Jacket / Hoodie', 'Jacket, hoodie, dan outer custom', 24, 1),
(7, 7, 'Kaos Sablon Manual', 'Kaos dengan sablon screen printing 1 warna', 24, 1),
(8, 10, 'Kaos Sablon Plastisol', 'Kaos distro dengan tinta plastisol premium', 24, 1),
(9, 8, 'Kaos Sablon DTF', 'Kaos custom full color dengan teknik digital DTF', 24, 1),
(10, 9, 'Kaos Sablon Polyflex', 'Kaos dengan sablon vinyl polyflex warna solid', 24, 1),
(11, 10, 'Kaos Glow in the Dark', 'Kaos sablon tinta fosfor menyala dalam gelap', 24, 1),
(12, 10, 'Kaos Sablon Rubber', 'Kaos sablon tinta karet elastis untuk kain gelap', 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_addons`
--
-- Creation: May 14, 2026 at 06:57 AM
-- Last update: May 14, 2026 at 06:57 AM
--

CREATE TABLE `product_addons` (
  `addon_id` int NOT NULL,
  `nama_addon` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_addon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biaya_tambahan` decimal(12,2) NOT NULL,
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `product_addons`:
--

--
-- Dumping data for table `product_addons`
--

INSERT INTO `product_addons` (`addon_id`, `nama_addon`, `jenis_addon`, `biaya_tambahan`, `satuan`, `keterangan`, `aktif`) VALUES
(1, 'Lengan Panjang +5k', 'lengan', 5000.00, 'per pcs', 'Tambahan lengan panjang untuk tshirt, polo, dan seragam olahraga.', 1),
(2, 'Big Size > XXL +5k', 'size', 5000.00, 'per pcs', 'Tambahan ukuran di atas XXL untuk tshirt, polo, seragam olahraga, dan jacket/hoodie.', 1),
(3, 'Big Size PDH > XL +10k', 'size', 10000.00, 'per pcs', 'Tambahan ukuran big size di atas XL untuk PDH.', 1),
(4, 'Custom Lengan Rompi +12k', 'lengan', 12000.00, 'per pcs', 'Lengan PDH bisa copot jadi model rompi.', 1),
(5, 'Logo 3D Jersey +30k', 'finishing', 30000.00, 'per logo', 'Plus logo 3D (Rubber/Bordir) untuk jersey.', 1),
(6, 'Kaos Kaki Jersey +45k', 'paket_tambahan', 45000.00, 'per pasang', 'Tambahan kaos kaki sebagai paket jersey.', 1),
(7, 'Lengan Panjang Jersey +10k', 'lengan', 10000.00, 'per pcs', 'Lengan panjang untuk semua tipe jersey.', 1),
(8, 'Big Size Jersey +10k', 'size', 10000.00, 'per pcs', 'Tambahan ukuran besar (bigsize) untuk jersey.', 1),
(9, 'Sablon Tambahan Warna', 'finishing', 8000.00, 'per warna', 'Biaya tambahan untuk setiap warna sablon tambahan', 1),
(10, 'Custom Desain Logo', 'finishing', 15000.00, 'per desain', 'Biaya untuk pembuatan desain logo custom sesuai permintaan', 1),
(11, 'Packing Plastik Premium', 'paket_tambahan', 3000.00, 'per pcs', 'Kemasan plastik premium untuk setiap produk jadi', 1),
(12, 'Label Merek Custom', 'finishing', 5000.00, 'per pcs', 'Penambahan label merek custom pada kaos atau hoodie', 1),
(13, 'Tambahan Bordir Nama', 'finishing', 10000.00, 'per nama', 'Bordir nama atau inisial pada bagian dada atau lengan', 1),
(14, 'Cetak Tag Size Custom', 'finishing', 4000.00, 'per pcs', 'Cetak label ukuran custom sesuai brand pelanggan', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--
-- Creation: May 14, 2026 at 06:57 AM
-- Last update: May 14, 2026 at 06:57 AM
--

CREATE TABLE `product_categories` (
  `category_id` int NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `product_categories`:
--

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`category_id`, `nama_kategori`, `deskripsi`, `aktif`) VALUES
(1, 'Tshirt', 'Kaos oblong, bahan cotton/semi cotton', 1),
(2, 'PDH', 'Pakaian Dinas Harian / Workshirt', 1),
(3, 'Jersey', 'Jersey tim, futsal, bola', 1),
(4, 'Polo Shirt', 'Kaos berkerah (polo)', 1),
(5, 'Seragam Olahraga', 'Seragam olahraga set baju + training', 1),
(6, 'Jacket/Hoodie', 'Jacket, hoodie, outer, dll.', 1),
(7, 'Sablon Manual', 'Produk kaos dengan sablon screen printing tradisional', 1),
(8, 'Sablon Digital', 'Produk kaos dengan sablon DTG (Direct to Garment)', 1),
(9, 'Sablon Polyflex', 'Produk kaos dengan sablon vinyl polyflex warna solid', 1),
(10, 'Sablon Premium', 'Produk kaos sablon plastisol, rubber, glow in the dark', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--
-- Creation: May 14, 2026 at 07:02 AM
-- Last update: May 14, 2026 at 06:57 AM
--

CREATE TABLE `product_variants` (
  `variant_id` int NOT NULL,
  `product_id` int NOT NULL,
  `nama_varian` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bahan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_sablon_bordir` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga_start_from` decimal(12,2) NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `product_variants`:
--   `product_id`
--       `products` -> `product_id`
--

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `nama_varian`, `bahan`, `tipe_sablon_bordir`, `harga_start_from`, `catatan`, `aktif`) VALUES
(1, 1, 'T-Shirt Semi Cotton / Polyester', 'Semi Cotton / Polyester', 'Sablon rubber', 55000.00, 'Minimal order 24 pcs.', 1),
(2, 1, 'T-Shirt Cotton Carded', 'Cotton Carded', 'Sablon plastisol', 60000.00, 'Minimal order 24 pcs.', 1),
(3, 1, 'T-Shirt Cotton Combed 24s', 'Cotton Combed 24s', 'Sablon plastisol / Rubber / DTF', 62000.00, 'Minimal order 24 pcs.', 1),
(4, 2, 'PDH Kain Unione', 'Kain Unione', 'Bordir maks 3 titik', 130000.00, 'Big size di atas XL +10k. Custom lengan bisa copot jadi rompi +12k.', 1),
(5, 2, 'PDH Kain American Drill', 'Kain American Drill', 'Bordir maks 3 titik', 135000.00, 'Big size di atas XL +10k. Custom lengan bisa copot jadi rompi +12k.', 1),
(6, 2, 'PDH Kain Nagata Drill', 'Kain Nagata Drill', 'Bordir maks 3 titik', 140000.00, 'Big size di atas XL +10k. Custom lengan bisa copot jadi rompi +12k.', 1),
(7, 3, 'Jersey Print Depan', NULL, 'Print depan + Polyflex (logo, nameset, no punggung)', 110000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(8, 3, 'Jersey Full Print Baju Celana Polyflex', 'Milano', 'Full print + Polyflex', 120000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(9, 3, 'Jersey Premium', 'Premium (Embose, Airwalk, Rabbit, dll.)', 'Premium finishing', 135000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(10, 3, 'Jersey Bahan Jaquard', 'Jaquard', NULL, 150000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(11, 3, 'Jersey Full Print Embose', 'Embose', 'Full print', 150000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(12, 3, 'Jersey Full Print Jaquard', 'Jaquard', 'Full print', 165000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(13, 3, 'Jersey Non Print', NULL, 'Sablon DTF / Polyflex', 100000.00, 'Lengan panjang +10k. Bigsize +10k.', 1),
(14, 3, 'Jersey Baju Atasan Saja', NULL, NULL, 95000.00, 'Atasan saja (tanpa celana). Lengan panjang +10k. Bigsize +10k.', 1),
(15, 4, 'Polo Shirt Full Premium Cotton 24s', 'Full Premium Cotton 24s', 'Bordir standar 2 titik; Sablon plastisol/DTF', 85000.00, 'Minimal order 24 pcs. Lengan panjang +5k. Di atas XXL +5k.', 1),
(16, 4, 'Polo Shirt Lacos 24s', 'Lacos 24s', 'Bordir standar 2 titik; Sablon DTF', 85000.00, 'Minimal order 24 pcs. Lengan panjang +5k. Di atas XXL +5k. Bonus tergantung kuantitas.', 1),
(17, 5, 'Seragam Olahraga Baju + Trining', 'Semi Cotton / TC + Trining Diadora', 'Sablon Rubber / DTF', 95000.00, 'Set baju + training. Minimal order 24 pcs. Lengan panjang +5k. Di atas XXL +5k.', 1),
(18, 6, 'Jacket / Hoodie Custom', 'Tergantung model & bahan', NULL, 120000.00, 'Harga mulai 120k s/d 200k per pcs, tergantung model & bahan. Minimal order 24 pcs. Bigsize di atas XXL +5k.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: May 14, 2026 at 06:57 AM
-- Last update: May 14, 2026 at 06:57 AM
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('kasir','owner') COLLATE utf8mb4_general_ci DEFAULT 'kasir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'owner', '72122ce96bfec66e2396d2e25225d70a', 'owner'),
(2, 'kasir', 'c7911af3adbd12a035b289556d96470a', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_customer` (`customer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_orders_status_tanggal` (`status_order`,`tanggal_order`),
  ADD KEY `idx_orders_customer_tanggal` (`customer_id`,`tanggal_order`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_orderitems_order` (`order_id`),
  ADD KEY `fk_orderitems_variant` (`variant_id`);

--
-- Indexes for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  ADD PRIMARY KEY (`order_item_addon_id`),
  ADD KEY `fk_itemaddons_item` (`order_item_id`),
  ADD KEY `fk_itemaddons_addon` (`addon_id`);

--
-- Indexes for table `order_item_sizes`
--
ALTER TABLE `order_item_sizes`
  ADD PRIMARY KEY (`order_item_size_id`),
  ADD KEY `fk_itemsizes_item` (`order_item_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payments_order` (`order_id`),
  ADD KEY `idx_payments_tanggal` (`tanggal_bayar`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `product_addons`
--
ALTER TABLE `product_addons`
  ADD PRIMARY KEY (`addon_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `fk_variants_product` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  MODIFY `order_item_addon_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_item_sizes`
--
ALTER TABLE `order_item_sizes`
  MODIFY `order_item_size_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_addons`
--
ALTER TABLE `product_addons`
  MODIFY `addon_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderitems_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_item_addons`
--
ALTER TABLE `order_item_addons`
  ADD CONSTRAINT `fk_itemaddons_addon` FOREIGN KEY (`addon_id`) REFERENCES `product_addons` (`addon_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_itemaddons_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_item_sizes`
--
ALTER TABLE `order_item_sizes`
  ADD CONSTRAINT `fk_itemsizes_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
