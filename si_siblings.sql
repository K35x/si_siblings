-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 03, 2026 at 12:42 AM
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
-- Table structure for table `category_sablon_types`
--
-- Creation: May 29, 2026 at 05:43 AM
--

CREATE TABLE `category_sablon_types` (
  `category_sablon_id` int NOT NULL,
  `category_id` int NOT NULL,
  `sablon_type_id` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `category_sablon_types`:
--   `category_id`
--       `product_categories` -> `category_id`
--   `sablon_type_id`
--       `sablon_types` -> `sablon_type_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--
-- Creation: May 29, 2026 at 12:06 AM
--

CREATE TABLE `colors` (
  `color_id` int NOT NULL,
  `color_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `colors`:
--

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`color_id`, `color_name`, `is_active`) VALUES
(1, 'Hitam', 1),
(2, 'Putih', 1),
(3, 'Merah', 1),
(4, 'Biru', 1),
(5, 'Hijau', 1),
(6, 'Kuning', 1),
(7, 'Orange', 1),
(8, 'Abu-abu', 1),
(9, 'Navy', 1),
(10, 'Maroon', 1),
(11, 'Ungu', 1);

--
-- Triggers `colors`
--
DELIMITER $$
CREATE TRIGGER `trg_colors_soft_delete` BEFORE DELETE ON `colors` FOR EACH ROW BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hard delete not allowed. Use SET is_active = 0';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--
-- Creation: May 30, 2026 at 02:49 PM
-- Last update: Jun 02, 2026 at 12:06 PM
--

CREATE TABLE `customers` (
  `customer_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `customers`:
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--
-- Creation: May 29, 2026 at 05:43 AM
-- Last update: Jun 02, 2026 at 12:06 PM
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `order_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `order_status` enum('pending_payment','confirmed','in_progress','ready','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_id` int DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_by_user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `orders`:
--   `cancelled_by_user_id`
--       `users` -> `user_id`
--   `customer_id`
--       `customers` -> `customer_id`
--   `user_id`
--       `users` -> `user_id`
--

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_orders_grand_total_check` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    IF NEW.grand_total < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'grand_total cannot be negative';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_orders_grand_total_check_update` BEFORE UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.grand_total < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'grand_total cannot be negative';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_orders_status_history` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF OLD.order_status != NEW.order_status THEN
        INSERT INTO order_status_history (order_id, from_status, to_status, changed_by_user_id, notes)
        VALUES (NEW.order_id, OLD.order_status, NEW.order_status, NEW.user_id, NULL);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_orders_status_transition` BEFORE UPDATE ON `orders` FOR EACH ROW BEGIN
    IF OLD.order_status != NEW.order_status THEN
        IF NEW.order_status = 'cancelled' AND OLD.order_status = 'completed' THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot cancel completed order';
        END IF;
        IF NEW.order_status != 'cancelled' THEN
            IF OLD.order_status = 'pending_payment' AND NEW.order_status != 'confirmed' THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'pending_payment can only transition to confirmed';
            END IF;
            IF OLD.order_status = 'confirmed' AND NEW.order_status NOT IN ('in_progress', 'cancelled') THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'confirmed can only transition to in_progress or cancelled';
            END IF;
            IF OLD.order_status = 'in_progress' AND NEW.order_status NOT IN ('ready', 'cancelled') THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'in_progress can only transition to ready or cancelled';
            END IF;
            IF OLD.order_status = 'ready' AND NEW.order_status NOT IN ('completed', 'cancelled') THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ready can only transition to completed or cancelled';
            END IF;
            IF OLD.order_status = 'completed' THEN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'completed orders cannot change status';
            END IF;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--
-- Creation: May 29, 2026 at 05:43 AM
-- Last update: Jun 02, 2026 at 12:06 PM
--

CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `product_name_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_name_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sablon_type_id` int DEFAULT NULL,
  `sablon_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,2) NOT NULL,
  `item_notes` text COLLATE utf8mb4_unicode_ci
) ;

--
-- RELATIONSHIPS FOR TABLE `order_items`:
--   `order_id`
--       `orders` -> `order_id`
--   `sablon_type_id`
--       `sablon_types` -> `sablon_type_id`
--   `variant_id`
--       `product_variants` -> `variant_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_item_designs`
--
-- Creation: May 29, 2026 at 05:43 AM
-- Last update: Jun 01, 2026 at 05:36 AM
--

CREATE TABLE `order_item_designs` (
  `design_id` int NOT NULL,
  `order_item_id` int NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `order_item_designs`:
--   `order_item_id`
--       `order_items` -> `order_item_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_item_details`
--
-- Creation: May 29, 2026 at 12:07 PM
-- Last update: Jun 02, 2026 at 12:06 PM
--

CREATE TABLE `order_item_details` (
  `order_item_detail_id` int NOT NULL,
  `order_item_id` int NOT NULL,
  `option_id` int DEFAULT NULL,
  `size_id` int NOT NULL,
  `color_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `sleeve_type` enum('short','long') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'short',
  `fulfillment_type` enum('ready_stock','custom') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `ready_qty` int NOT NULL DEFAULT '0',
  `custom_color` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ;

--
-- RELATIONSHIPS FOR TABLE `order_item_details`:
--   `color_id`
--       `colors` -> `color_id`
--   `option_id`
--       `variant_options` -> `option_id`
--   `order_item_id`
--       `order_items` -> `order_item_id`
--   `size_id`
--       `sizes` -> `size_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--
-- Creation: May 28, 2026 at 08:50 PM
-- Last update: Jun 02, 2026 at 12:06 PM
--

CREATE TABLE `order_status_history` (
  `history_id` int NOT NULL,
  `order_id` int NOT NULL,
  `from_status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by_user_id` int DEFAULT NULL,
  `changed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `order_status_history`:
--   `order_id`
--       `orders` -> `order_id`
--   `changed_by_user_id`
--       `users` -> `user_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--
-- Creation: May 29, 2026 at 05:43 AM
-- Last update: Jun 02, 2026 at 01:00 AM
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_method` enum('cash','transfer','qris','debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_status` enum('pending','paid','void','refunded') COLLATE utf8mb4_unicode_ci NOT NULL,
  `received_by_user_id` int DEFAULT NULL,
  `voided_by_user_id` int DEFAULT NULL,
  `refunded_by_user_id` int DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `voided_at` datetime DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL,
  `reference_payment_id` int DEFAULT NULL
) ;

--
-- RELATIONSHIPS FOR TABLE `payments`:
--   `order_id`
--       `orders` -> `order_id`
--   `received_by_user_id`
--       `users` -> `user_id`
--   `reference_payment_id`
--       `payments` -> `payment_id`
--   `refunded_by_user_id`
--       `users` -> `user_id`
--   `voided_by_user_id`
--       `users` -> `user_id`
--

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `trg_payments_status_consistency_insert` BEFORE INSERT ON `payments` FOR EACH ROW BEGIN
    IF NEW.payment_status = 'paid' AND (NEW.received_by_user_id IS NULL OR NEW.paid_at IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'paid payments must have received_by_user_id and paid_at';
    END IF;
    IF NEW.payment_status = 'void' AND (NEW.voided_by_user_id IS NULL OR NEW.voided_at IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'void payments must have voided_by_user_id and voided_at';
    END IF;
    IF NEW.payment_status = 'refunded' AND (NEW.refunded_by_user_id IS NULL OR NEW.refunded_at IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'refunded payments must have refunded_by_user_id and refunded_at';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_payments_status_consistency_update` BEFORE UPDATE ON `payments` FOR EACH ROW BEGIN
    IF NEW.payment_status = 'paid' AND (NEW.received_by_user_id IS NULL OR NEW.paid_at IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'paid payments must have received_by_user_id and paid_at';
    END IF;
    IF NEW.payment_status = 'void' AND (NEW.voided_by_user_id IS NULL OR NEW.voided_at IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'void payments must have voided_by_user_id and voided_at';
    END IF;
    IF NEW.payment_status = 'refunded' AND (NEW.refunded_by_user_id IS NULL OR NEW.refunded_at IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'refunded payments must have refunded_by_user_id and refunded_at';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
-- Creation: May 29, 2026 at 05:43 AM
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  `product_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_order` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `products`:
--   `category_id`
--       `product_categories` -> `category_id`
--

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `trg_products_soft_delete` BEFORE DELETE ON `products` FOR EACH ROW BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hard delete not allowed. Use SET is_active = 0';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--
-- Creation: May 29, 2026 at 05:43 AM
--

CREATE TABLE `product_categories` (
  `category_id` int NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `product_categories`:
--

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`category_id`, `category_name`, `is_active`) VALUES
(1, 'T-Shirt', 1),
(2, 'Jersey', 1),
(4, 'Polo Shirt', 1),
(5, 'Seragam Olahraga', 1),
(6, 'Jacket', 1),
(7, 'Hoodie', 1),
(8, 'PDH', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--
-- Creation: May 29, 2026 at 05:43 AM
-- Last update: Jun 02, 2026 at 11:46 AM
--

CREATE TABLE `product_variants` (
  `variant_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `sleeve_price` decimal(12,2) NOT NULL DEFAULT '5000.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `product_variants`:
--   `product_id`
--       `products` -> `product_id`
--

--
-- Triggers `product_variants`
--
DELIMITER $$
CREATE TRIGGER `trg_variants_soft_delete` BEFORE DELETE ON `product_variants` FOR EACH ROW BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hard delete not allowed. Use SET is_active = 0';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sablon_types`
--
-- Creation: May 29, 2026 at 05:43 AM
--

CREATE TABLE `sablon_types` (
  `sablon_type_id` int NOT NULL,
  `sablon_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `sablon_types`:
--

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--
-- Creation: May 29, 2026 at 12:06 AM
--

CREATE TABLE `sizes` (
  `size_id` int NOT NULL,
  `size_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `sizes`:
--

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`size_id`, `size_name`, `is_active`) VALUES
(1, 'S', 1),
(2, 'M', 1),
(3, 'L', 1),
(4, 'XL', 1),
(5, 'XXL', 1),
(6, '3XL', 1);

--
-- Triggers `sizes`
--
DELIMITER $$
CREATE TRIGGER `trg_sizes_soft_delete` BEFORE DELETE ON `sizes` FOR EACH ROW BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hard delete not allowed. Use SET is_active = 0';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: May 29, 2026 at 05:43 AM
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('kasir','owner') COLLATE utf8mb4_unicode_ci DEFAULT 'kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `is_active`) VALUES
(1, 'kasir1', '$2y$12$dzI8.DRkqBeLUKbk9W/nZezWSVxcaAJlmu5O.oSH7ZvfYJwNCZJMm', 'kasir', 1),
(2, 'owner1', '$2y$12$Arue.vuHgqbpO2KgU8bzfuMkZscsU91eEQEWoHchEcHvkFw9dzUd2', 'owner', 1),
(3, 'kasir2', '$2y$12$0ygCUCsA/5/gQd9efnK.C.x1gTAfSzVN0NyH7Nq7Wg1xrbuB95TYG', 'kasir', 1),
(4, 'owner2', '$2y$12$9Ru.a0VUM6e1bAtGGYwW1eRximUaQPqRhwL.Eoi1tqEC6mlTIdKIK', 'owner', 1);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `trg_users_soft_delete` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hard delete not allowed. Use SET is_active = 0';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--
-- Creation: May 29, 2026 at 01:18 AM
-- Last update: Jun 02, 2026 at 12:05 PM
--

CREATE TABLE `variant_options` (
  `option_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `size_id` int NOT NULL,
  `color_id` int NOT NULL,
  `sleeve_type` enum('short','long') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'short',
  `quantity` int NOT NULL DEFAULT '1',
  `price_surcharge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ;

--
-- RELATIONSHIPS FOR TABLE `variant_options`:
--   `variant_id`
--       `product_variants` -> `variant_id`
--   `size_id`
--       `sizes` -> `size_id`
--   `color_id`
--       `colors` -> `color_id`
--

--
-- Triggers `variant_options`
--
DELIMITER $$
CREATE TRIGGER `trg_variant_options_soft_delete` BEFORE DELETE ON `variant_options` FOR EACH ROW BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hard delete not allowed. Use SET is_active = 0';
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_sablon_types`
--
ALTER TABLE `category_sablon_types`
  ADD PRIMARY KEY (`category_sablon_id`),
  ADD UNIQUE KEY `uq_category_sablon` (`category_id`,`sablon_type_id`),
  ADD KEY `fk_cst_sablon` (`sablon_type_id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `uq_customer_name_phone` (`name`,`phone_number`),
  ADD KEY `idx_customers_name` (`name`),
  ADD KEY `idx_customers_phone_number` (`phone_number`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_walkincustomer` (`customer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_orders_cancelled_by` (`cancelled_by_user_id`),
  ADD KEY `idx_orders_order_status` (`order_status`),
  ADD KEY `idx_orders_order_date` (`order_date`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_orderitems_order` (`order_id`),
  ADD KEY `fk_orderitems_variant` (`variant_id`),
  ADD KEY `fk_orderitems_sablon_type` (`sablon_type_id`);

--
-- Indexes for table `order_item_designs`
--
ALTER TABLE `order_item_designs`
  ADD PRIMARY KEY (`design_id`),
  ADD KEY `fk_oidsgn_order_item` (`order_item_id`);

--
-- Indexes for table `order_item_details`
--
ALTER TABLE `order_item_details`
  ADD PRIMARY KEY (`order_item_detail_id`),
  ADD KEY `fk_oid_order_item` (`order_item_id`),
  ADD KEY `fk_oid_option` (`option_id`),
  ADD KEY `fk_oid_size` (`size_id`),
  ADD KEY `fk_oid_color` (`color_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_osh_order` (`order_id`),
  ADD KEY `fk_osh_user` (`changed_by_user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payments_order` (`order_id`),
  ADD KEY `fk_payments_received_by` (`received_by_user_id`),
  ADD KEY `fk_payments_voided_by` (`voided_by_user_id`),
  ADD KEY `fk_payments_refunded_by` (`refunded_by_user_id`),
  ADD KEY `idx_payments_payment_status` (`payment_status`),
  ADD KEY `idx_payments_reference` (`reference_payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `idx_products_product_name` (`product_name`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uk_category_name` (`category_name`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `fk_variants_product` (`product_id`);

--
-- Indexes for table `sablon_types`
--
ALTER TABLE `sablon_types`
  ADD PRIMARY KEY (`sablon_type_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `uk_variant_sleeve_type` (`variant_id`,`size_id`,`color_id`,`sleeve_type`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `color_id` (`color_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_sablon_types`
--
ALTER TABLE `category_sablon_types`
  MODIFY `category_sablon_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `color_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item_designs`
--
ALTER TABLE `order_item_designs`
  MODIFY `design_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item_details`
--
ALTER TABLE `order_item_details`
  MODIFY `order_item_detail_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `history_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sablon_types`
--
ALTER TABLE `sablon_types`
  MODIFY `sablon_type_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `size_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `option_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_sablon_types`
--
ALTER TABLE `category_sablon_types`
  ADD CONSTRAINT `fk_cst_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`),
  ADD CONSTRAINT `fk_cst_sablon` FOREIGN KEY (`sablon_type_id`) REFERENCES `sablon_types` (`sablon_type_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_cancelled_by` FOREIGN KEY (`cancelled_by_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_orders_walkincustomer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderitems_sablon_type` FOREIGN KEY (`sablon_type_id`) REFERENCES `sablon_types` (`sablon_type_id`),
  ADD CONSTRAINT `fk_orderitems_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_item_designs`
--
ALTER TABLE `order_item_designs`
  ADD CONSTRAINT `fk_oidsgn_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_item_details`
--
ALTER TABLE `order_item_details`
  ADD CONSTRAINT `fk_oid_color` FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`),
  ADD CONSTRAINT `fk_oid_option` FOREIGN KEY (`option_id`) REFERENCES `variant_options` (`option_id`),
  ADD CONSTRAINT `fk_oid_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_oid_size` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`);

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_osh_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_osh_user` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payments_received_by` FOREIGN KEY (`received_by_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_payments_reference` FOREIGN KEY (`reference_payment_id`) REFERENCES `payments` (`payment_id`),
  ADD CONSTRAINT `fk_payments_refunded_by` FOREIGN KEY (`refunded_by_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_payments_voided_by` FOREIGN KEY (`voided_by_user_id`) REFERENCES `users` (`user_id`);

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

--
-- Constraints for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD CONSTRAINT `variant_options_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variant_options_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`size_id`),
  ADD CONSTRAINT `variant_options_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `colors` (`color_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
