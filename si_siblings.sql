-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Bulan Mei 2026 pada 03.46
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
-- Database: `si_siblings`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`customer_id`, `nama`, `no_hp`, `catatan`) VALUES
(1, 'Komunitas Futsal A', '081234567890', 'Order jersey mingguan'),
(2, 'BEM Politeknik XYZ', '089876543210', 'Order kaos acara kampus'),
(3, 'Umum - Tanpa Nama', NULL, 'Pelanggan tanpa identitas lengkap'),
(4, 'Komunitas Basket B', '081345678901', 'Order jersey basket bulanan'),
(5, 'SMK Negeri 1 XYZ', '082234567890', 'Order seragam olahraga sekolah'),
(6, 'Event Organizer ABC', '083345678901', 'Order kaos panitia acara'),
(7, 'Komunitas Motor Brotherhood', '084456789012', 'Order jaket komunitas custom'),
(8, 'Startup Creative Studio', '085567890123', 'Order hoodie sablon untuk tim'),
(9, 'Umum - Pelanggan Walk-in', NULL, 'Order kaos sablon custom tanpa data lengkap');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `tanggal_order` datetime NOT NULL,
  `status_order` varchar(30) NOT NULL,
  `catatan` text DEFAULT NULL,
  `total_qty` int(11) NOT NULL DEFAULT 0,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_addon` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`order_id`, `order_code`, `customer_id`, `tanggal_order`, `status_order`, `catatan`, `total_qty`, `subtotal`, `total_addon`, `grand_total`, `user_id`) VALUES
(1, 'INV-2026-0001', NULL, '2026-05-02 12:21:21', 'done', 'Order Tshirt Combed 24s size S & XXL, kombinasi lengan pendek/panjang', 46, 2852000.00, 0.00, 2852000.00, NULL),
(2, 'INV-2026-0002', 2, '2026-05-13 10:14:35', 'pending', 'Order PDH Drill untuk BEM', 30, 3900000.00, 300000.00, 4200000.00, 1),
(3, 'INV-2026-0017', 2, '2026-05-13 13:42:21', 'done', 'Order PDH Drill untuk BEM', 30, 3900000.00, 0.00, 4200000.00, 1),
(4, 'INV-2026-0018', 4, '2026-05-13 13:42:21', 'done', 'Order Polo Shirt', 24, 2400000.00, 0.00, 2400000.00, 10),
(5, 'INV-2026-0019', 5, '2026-05-13 13:42:21', 'processing', 'Hoddie/jaket', 34, 30000000.00, 0.00, 30000000.00, 5),
(6, 'INV-2026-0020', 6, '2026-05-13 13:42:21', 'pending', 'Hoddie/jaket', 35, 35000000.00, 0.00, 35000000.00, 5),
(17, 'INV-2026-0003', 2, '2026-05-05 14:30:00', 'pending', 'Order PDH Drill untuk BEM', 30, 3900000.00, 0.00, 3900000.00, 1),
(18, 'INV-2026-0004', 1, '2026-05-06 09:15:00', 'processing', 'Order jersey futsal sablon polyflex, size M & L', 25, 2500000.00, 200000.00, 2700000.00, 2),
(19, 'INV-2026-0005', 4, '2026-05-07 16:45:00', 'done', 'Order polo premium bordir logo perusahaan', 20, 1900000.00, 150000.00, 2050000.00, 2),
(20, 'INV-2026-0006', 5, '2026-05-08 11:00:00', 'pending', 'Order seragam olahraga sekolah sablon sublimasi', 60, 4800000.00, 0.00, 4800000.00, 3),
(21, 'INV-2026-0007', 6, '2026-05-09 13:20:00', 'processing', 'Order hoodie fleece custom sablon rubber', 15, 2250000.00, 150000.00, 2400000.00, 2),
(22, 'INV-2026-0008', 7, '2026-05-10 10:00:00', 'pending', 'Order kaos sablon manual 1 warna', 24, 1560000.00, 192000.00, 1752000.00, 1),
(23, 'INV-2026-0009', 8, '2026-05-10 14:30:00', 'done', 'Order kaos sablon DTG full color', 12, 1440000.00, 0.00, 1440000.00, 3),
(24, 'INV-2026-0010', 9, '2026-05-11 09:00:00', 'processing', 'Order kaos polyflex nameset tim futsal', 18, 1710000.00, 90000.00, 1800000.00, 2),
(25, 'INV-2026-0011', 1, '2026-05-11 15:00:00', 'pending', 'Order kaos glow in the dark sablon fosfor', 24, 1920000.00, 240000.00, 2160000.00, 1),
(26, 'INV-2026-0012', 2, '2026-05-12 08:45:00', 'done', 'Order kaos sablon rubber untuk komunitas motor', 36, 3240000.00, 180000.00, 3420000.00, 2),
(27, 'INV-2026-0013', 2, '2026-05-12 13:30:00', 'processing', 'Order polo bordir custom untuk startup', 20, 2000000.00, 0.00, 2000000.00, 3),
(28, 'INV-2026-0014', 3, '2026-05-12 17:00:00', 'pending', 'Order jersey basket sablon polyflex full print', 30, 3000000.00, 300000.00, 3300000.00, 1),
(29, 'INV-2026-0015', 4, '2026-05-13 09:30:00', 'done', 'Order kaos sablon plastisol premium distro', 24, 2880000.00, 240000.00, 3120000.00, 2),
(30, 'INV-2026-0016', 5, '2026-05-13 14:00:00', 'processing', 'Order hoodie sablon DTG full color untuk EO', 12, 1800000.00, 120000.00, 1920000.00, 3);

--
-- Trigger `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_order_code` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
  DECLARE next_number INT;
  DECLARE tahun CHAR(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  SET tahun = DATE_FORMAT(NEW.tanggal_order, '%Y');

  SET next_number = (
    SELECT IFNULL(MAX(CAST(SUBSTRING(order_code, 10) AS UNSIGNED)), 0) + 1
    FROM orders
    WHERE SUBSTRING(order_code, 5, 4) = tahun
  );

  SET NEW.order_code = CONCAT('INV-', tahun, '-', LPAD(next_number, 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `desain_referensi` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `catatan_item` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `variant_id`, `desain_referensi`, `qty`, `harga_satuan`, `subtotal`, `catatan_item`) VALUES
(1, 1, 1, 'dsgn_2026_0001.png', 10, 62000.00, 620000.00, 'Kaos S lengan pendek'),
(2, 1, 1, 'dsgn_2026_0001.png', 36, 62000.00, 2232000.00, 'Kaos S & XXL lengan panjang'),
(3, 2, 4, 'dsgn_2026_0002.png', 15, 130000.00, 1950000.00, 'PDH Unione bordir logo kampus'),
(4, 2, 5, 'dsgn_2026_0002.png', 15, 135000.00, 2025000.00, 'PDH American Drill bordir 3 titik'),
(5, 17, 7, 'dsgn_2026_0003.png', 20, 100000.00, 2000000.00, 'Jersey futsal sablon polyflex depan'),
(6, 18, 8, 'dsgn_2026_0003.png', 30, 120000.00, 3600000.00, 'Jersey futsal full print polyflex setelan'),
(7, 21, 9, 'dsgn_2026_0004.png', 12, 95000.00, 1140000.00, 'Polo premium cotton bordir logo'),
(8, 1, 10, 'dsgn_2026_0005.png', 40, 85000.00, 3400000.00, 'Seragam olahraga dryfit sublimasi'),
(9, 23, 11, 'dsgn_2026_0006.png', 10, 150000.00, 1500000.00, 'Hoodie fleece custom sablon rubber'),
(10, 17, 12, 'dsgn_2026_0007.png', 24, 65000.00, 1560000.00, 'Kaos sablon manual 1 warna'),
(11, 1, 13, 'dsgn_2026_0008.png', 12, 120000.00, 1440000.00, 'Kaos sablon DTG full color'),
(12, 28, 14, 'dsgn_2026_0009.png', 18, 95000.00, 1710000.00, 'Kaos polyflex nameset tim futsal'),
(13, 30, 5, 'dsgn_2026_0010.png', 24, 80000.00, 1920000.00, 'Kaos glow in the dark sablon fosfor'),
(14, 17, 6, 'dsgn_2026_0011.png', 36, 90000.00, 3240000.00, 'Kaos sablon rubber komunitas motor'),
(15, 30, 7, 'dsgn_2026_0012.png', 20, 100000.00, 2000000.00, 'Polo bordir custom startup'),
(16, 24, 8, 'dsgn_2026_0013.png', 30, 110000.00, 3300000.00, 'Jersey basket sablon polyflex full print'),
(17, 19, 9, 'dsgn_2026_0014.png', 24, 120000.00, 2880000.00, 'Kaos sablon plastisol premium distro');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_item_addons`
--

CREATE TABLE `order_item_addons` (
  `order_item_addon_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `biaya_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_item_addons`
--

INSERT INTO `order_item_addons` (`order_item_addon_id`, `order_item_id`, `addon_id`, `qty`, `biaya_satuan`, `subtotal`) VALUES
(1, 2, 1, 36, 5000.00, 180000.00),
(2, 2, 2, 26, 5000.00, 130000.00),
(3, 3, 5, 15, 30000.00, 450000.00),
(4, 4, 4, 15, 12000.00, 180000.00),
(5, 5, 6, 20, 45000.00, 900000.00),
(6, 6, 1, 10, 5000.00, 50000.00),
(7, 7, 2, 24, 5000.00, 120000.00),
(8, 8, 5, 12, 30000.00, 360000.00),
(9, 9, 2, 18, 5000.00, 90000.00),
(10, 10, 1, 24, 5000.00, 120000.00),
(11, 11, 5, 36, 30000.00, 1080000.00),
(12, 12, 4, 20, 12000.00, 240000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_item_sizes`
--

CREATE TABLE `order_item_sizes` (
  `order_item_size_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `ukuran` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_item_sizes`
--

INSERT INTO `order_item_sizes` (`order_item_size_id`, `order_item_id`, `ukuran`, `qty`) VALUES
(1, 1, 'S', 10),
(2, 2, 'S', 10),
(3, 2, 'XXL', 26),
(4, 3, 'M', 8),
(5, 3, 'L', 7),
(6, 4, 'XL', 10),
(7, 5, 'M', 12),
(8, 5, 'L', 8),
(9, 6, 'XL', 10),
(10, 7, 'S', 12),
(11, 7, 'M', 12),
(12, 8, 'L', 6),
(13, 8, 'XL', 6),
(14, 9, 'M', 10),
(15, 9, 'L', 8),
(16, 10, 'S', 12),
(17, 10, 'XL', 12),
(18, 11, 'L', 18),
(19, 11, 'XL', 18),
(20, 12, 'M', 10),
(21, 12, 'L', 10),
(22, 13, 'M', 15),
(23, 13, 'L', 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `jumlah_bayar` decimal(12,2) NOT NULL,
  `status_bayar` varchar(30) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `tanggal_bayar`, `metode_bayar`, `jumlah_bayar`, `status_bayar`, `keterangan`) VALUES
(1, 1, '2026-05-02 12:22:13', 'Tunai', 1581000.00, 'paid', 'DP 50%'),
(2, 1, '2026-05-02 12:22:13', 'Tunai', 1581000.00, 'paid', 'Pelunasan'),
(3, 2, '2026-05-05 15:00:00', 'Transfer Bank', 2100000.00, 'paid', 'DP 50% untuk order PDH Drill'),
(4, 2, '2026-05-06 10:00:00', 'Transfer Bank', 2100000.00, 'paid', 'Pelunasan order PDH Drill'),
(5, 17, '2026-05-06 09:30:00', 'E-Wallet', 2625000.00, 'paid', 'DP 50% untuk order kaos acara kampus'),
(6, 18, '2026-05-07 14:00:00', 'E-Wallet', 2625000.00, 'paid', 'Pelunasan order kaos acara kampus'),
(7, 19, '2026-05-07 17:00:00', 'Tunai', 1050000.00, 'paid', 'Pembayaran penuh polo premium'),
(8, 20, '2026-05-08 11:30:00', 'Transfer Bank', 2550000.00, 'paid', 'DP 50% seragam olahraga sekolah'),
(9, 21, '2026-05-09 09:00:00', 'Transfer Bank', 2550000.00, 'paid', 'Pelunasan seragam olahraga sekolah'),
(10, 22, '2026-05-09 13:30:00', 'E-Wallet', 1200000.00, 'paid', 'DP hoodie fleece custom'),
(11, 23, '2026-05-10 10:00:00', 'E-Wallet', 1200000.00, 'paid', 'Pelunasan hoodie fleece custom'),
(12, 24, '2026-05-10 10:30:00', 'Tunai', 1752000.00, 'paid', 'Pembayaran penuh kaos sablon manual'),
(13, 25, '2026-05-10 15:00:00', 'Transfer Bank', 810000.00, 'paid', 'DP kaos sablon DTG full color'),
(14, 26, '2026-05-11 09:00:00', 'Transfer Bank', 810000.00, 'paid', 'Pelunasan kaos sablon DTG full color'),
(15, 27, '2026-05-11 09:30:00', 'E-Wallet', 900000.00, 'paid', 'DP kaos polyflex nameset'),
(16, 28, '2026-05-12 14:00:00', 'E-Wallet', 900000.00, 'paid', 'Pelunasan kaos polyflex nameset');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `minimal_order` int(11) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `nama_produk`, `deskripsi`, `minimal_order`, `aktif`) VALUES
(1, 1, 'Tshirt Basic', 'Kaos basic Siblings.co', 24, 1),
(2, 2, 'PDH Bahan Drill', 'PDH bahan Unione/American/Nagata Drill', 24, 1),
(3, 3, 'Jersey Setelan', 'Jersey baju+celana', 24, 1),
(4, 4, 'Polo Shirt Premium', 'Polo bahan premium', 24, 1),
(5, 5, 'Seragam Olahraga Set', 'Baju + training', 24, 1),
(6, 6, 'Hoodie/Jaket Custom', 'Model dan bahan custom', 24, 1),
(7, 7, 'Kaos Sablon Manual', 'Kaos dengan sablon screen printing 1 warna', 24, 1),
(8, 10, 'Kaos Sablon Plastisol', 'Kaos distro dengan tinta plastisol premium', 24, 1),
(9, 8, 'Kaos Sablon DTF', 'Kaos custom full color dengan teknik digital DTF', 24, 1),
(10, 9, 'Kaos Sablon Polyflex', 'Kaos dengan sablon vinyl polyflex warna solid', 24, 1),
(11, 10, 'Kaos Glow in the Dark', 'Kaos sablon tinta fosfor menyala dalam gelap', 24, 1),
(12, 10, 'Kaos Sablon Rubber', 'Kaos sablon tinta karet elastis untuk kain gelap', 24, 1);

--
-- Trigger `products`
--
DELIMITER $$
CREATE TRIGGER `trg_minimal_order_update` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    IF NEW.minimal_order < 24 THEN
        SET NEW.minimal_order = 24;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_addons`
--

CREATE TABLE `product_addons` (
  `addon_id` int(11) NOT NULL,
  `nama_addon` varchar(150) NOT NULL,
  `jenis_addon` varchar(50) DEFAULT NULL,
  `biaya_tambahan` decimal(12,2) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `product_addons`
--

INSERT INTO `product_addons` (`addon_id`, `nama_addon`, `jenis_addon`, `biaya_tambahan`, `satuan`, `keterangan`, `aktif`) VALUES
(1, 'Lengan Panjang', 'lengan', 5000.00, 'per pcs', 'Tambahan biaya untuk lengan panjang', 1),
(2, 'Big Size > XL', 'size', 5000.00, 'per pcs', 'Tambahan size di atas XXL untuk tshirt/polo/seragam', 1),
(3, 'Big Size PDH > XL', 'size', 10000.00, 'per pcs', 'Tambahan big size PDH di atas XL', 1),
(4, 'Custom Lengan Rompi', 'lengan', 12000.00, 'per pcs', 'PDH dengan lengan bisa copot jadi rompi', 1),
(5, 'Logo 3D (Rubber/Bordir)', 'finishing', 30000.00, 'per logo', 'Logo timbul 3D', 1),
(6, 'Kaos Kaki Jersey', 'paket_tambahan', 45000.00, 'per pasang', 'Kaos kaki tambahan untuk jersey', 1),
(7, 'Sablon Tambahan Warna', 'finishing', 8000.00, 'per warna', 'Biaya tambahan untuk setiap warna sablon tambahan', 1),
(8, 'Custom Desain Logo', 'finishing', 15000.00, 'per desain', 'Biaya untuk pembuatan desain logo custom sesuai permintaan', 1),
(9, 'Packing Plastik Premium', 'paket_tambahan', 3000.00, 'per pcs', 'Kemasan plastik premium untuk setiap produk jadi', 1),
(10, 'Label Merek Custom', 'finishing', 5000.00, 'per pcs', 'Penambahan label merek custom pada kaos atau hoodie', 1),
(11, 'Tambahan Bordir Nama', 'finishing', 10000.00, 'per nama', 'Bordir nama atau inisial pada bagian dada atau lengan', 1),
(12, 'Cetak Tag Size Custom', 'finishing', 4000.00, 'per pcs', 'Cetak label ukuran custom sesuai brand pelanggan', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_categories`
--

CREATE TABLE `product_categories` (
  `category_id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `product_categories`
--

INSERT INTO `product_categories` (`category_id`, `nama_kategori`, `deskripsi`, `aktif`) VALUES
(1, 'Tshirt', 'Kaos oblong, bahan cotton combed/carded', 1),
(2, 'PDH', 'Pakaian Dinas Harian', 1),
(3, 'Jersey', 'Jersey bola/futsal', 1),
(4, 'Polo Shirt', 'Kaos kerah', 1),
(5, 'Seragam Olahraga', 'Seragam olahraga sekolah/kantor', 1),
(6, 'Jacket & Hoodie', 'Jaket dan hoodie', 1),
(7, 'Sablon Manual', 'Produk kaos dengan sablon screen printing tradisional', 1),
(8, 'Sablon Digital', 'Produk kaos dengan sablon DTG (Direct to Garment)', 1),
(9, 'Sablon Polyflex', 'Produk kaos dengan sablon vinyl polyflex warna solid', 1),
(10, 'Sablon Premium', 'Produk kaos sablon plastisol, rubber, glow in the dark', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_color`
--

CREATE TABLE `product_color` (
  `color_id` int(11) NOT NULL,
  `color_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_color`
--

INSERT INTO `product_color` (`color_id`, `color_name`) VALUES
(1, 'Merah'),
(2, 'Jingga'),
(3, 'Kuning'),
(4, 'Hijau'),
(5, 'Biru'),
(6, 'Nila'),
(7, 'Ungu'),
(8, 'Hitam'),
(9, 'Putih');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_size`
--

CREATE TABLE `product_size` (
  `size_id` int(11) NOT NULL,
  `size_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_size`
--

INSERT INTO `product_size` (`size_id`, `size_name`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `nama_varian` varchar(150) NOT NULL,
  `bahan` varchar(150) DEFAULT NULL,
  `tipe_sablon_bordir` varchar(150) DEFAULT NULL,
  `harga_start_from` decimal(12,2) NOT NULL,
  `catatan` text DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `nama_varian`, `bahan`, `tipe_sablon_bordir`, `harga_start_from`, `catatan`, `aktif`) VALUES
(1, 1, 'Cotton Combed 24s', 'Cotton Combed 24s', 'Plastisol / Rubber / DTF', 62000.00, 'Minimal order 24 pcs. Lengan panjang +5k, di atas XXL +5k.', 1),
(2, 1, 'Semi Cotton / Polyester', 'Semi Cotton / Polyester', 'Rubber', 55000.00, 'Minimal order 24 pcs. Lengan panjang +5k, di atas XXL +5k.', 1),
(3, 1, 'Cotton Carded', 'Cotton Carded', 'Plastisol', 60000.00, 'Minimal order 24 pcs. Lengan panjang +5k, di atas XXL +5k.', 1),
(4, 2, 'PDH Unione 130k', 'Unione', 'Bordir maks 3 titik', 130000.00, 'Ukuran big size di atas XL +10k. Custom lengan rompi +12k.', 1),
(5, 2, 'PDH American Drill 135k', 'American Drill', 'Bordir maks 3 titik', 135000.00, 'Ukuran big size di atas XL +10k. Custom lengan rompi +12k.', 1),
(6, 2, 'PDH Nagata Drill 140k', 'Nagata Drill', 'Bordir maks 3 titik', 140000.00, 'Ukuran big size di atas XL +10k. Custom lengan rompi +12k.', 1),
(7, 3, 'Print Depan Polyflex', 'Milano', 'Polyflex (logo, nameset, no punggung)', 110000.00, 'Lengan panjang +10k, big size +10k.', 1),
(8, 3, 'Full Print Polyflex Setelan', 'Milano', 'Full Print Polyflex', 120000.00, 'Lengan panjang +10k, big size +10k.', 1),
(9, 4, 'Polo Premium Cotton', 'Cotton Pique Premium', 'Plastisol / Bordir', 95000.00, 'Minimal order 24 pcs. Bordir logo +30k.', 1),
(10, 5, 'Seragam Olahraga Dryfit', 'Dryfit Polyester', 'Polyflex / Sublimasi', 85000.00, 'Minimal order 24 pcs. Tambahan nama +10k.', 1),
(11, 6, 'Hoodie Fleece Custom', 'Fleece Cotton', 'Rubber / DTG', 150000.00, 'Minimal order 12 pcs. Glow in the dark +20k.', 1),
(12, 7, 'Kaos Sablon Manual 1 Warna', 'Cotton Combed 30s', 'Screen Printing', 65000.00, 'Minimal order 24 pcs. Tambahan warna +8k.', 1),
(13, 8, 'Kaos Sablon DTG Full Color', 'Cotton Combed 24s', 'DTG Digital Printing', 120000.00, 'Minimal order 12 pcs. Custom desain logo +15k.', 1),
(14, 9, 'Kaos Polyflex Nameset', 'Polyester Dryfit', 'Polyflex', 95000.00, 'Minimal order 12 pcs. Tambahan nomor +5k.', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_variant_options`
--

CREATE TABLE `product_variant_options` (
  `option_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_variant_options`
--

INSERT INTO `product_variant_options` (`option_id`, `variant_id`, `size_id`, `color_id`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 2),
(3, 3, 1, 8),
(4, 1, 2, 9),
(5, 1, 3, 5),
(6, 1, 4, 6),
(7, 1, 5, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('kasir','owner') DEFAULT 'kasir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'immanuel', 'passwordku123', 'owner'),
(2, 'Kamal', 'kamal123', 'kasir'),
(3, 'kalyca', 'kalyca123', 'kasir'),
(4, 'admin_sablon', 'admin2026', 'owner'),
(5, 'rina', 'rina123', 'kasir'),
(6, 'agus', 'agus123', 'kasir'),
(7, 'budi', 'budi123', 'owner'),
(8, 'sari', 'sari123', 'kasir'),
(9, 'eko', 'eko123', 'kasir'),
(10, 'lia', 'lia123', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_walkincustomer` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_orderitems_order` (`order_id`),
  ADD KEY `fk_orderitems_variant` (`variant_id`);

--
-- Indeks untuk tabel `order_item_addons`
--
ALTER TABLE `order_item_addons`
  ADD PRIMARY KEY (`order_item_addon_id`),
  ADD KEY `fk_itemaddons_item` (`order_item_id`),
  ADD KEY `fk_itemaddons_addon` (`addon_id`);

--
-- Indeks untuk tabel `order_item_sizes`
--
ALTER TABLE `order_item_sizes`
  ADD PRIMARY KEY (`order_item_size_id`),
  ADD KEY `fk_itemsizes_item` (`order_item_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payments_order` (`order_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indeks untuk tabel `product_addons`
--
ALTER TABLE `product_addons`
  ADD PRIMARY KEY (`addon_id`);

--
-- Indeks untuk tabel `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeks untuk tabel `product_color`
--
ALTER TABLE `product_color`
  ADD PRIMARY KEY (`color_id`);

--
-- Indeks untuk tabel `product_size`
--
ALTER TABLE `product_size`
  ADD PRIMARY KEY (`size_id`);

--
-- Indeks untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `fk_variants_product` (`product_id`);

--
-- Indeks untuk tabel `product_variant_options`
--
ALTER TABLE `product_variant_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `order_item_addons`
--
ALTER TABLE `order_item_addons`
  MODIFY `order_item_addon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `order_item_sizes`
--
ALTER TABLE `order_item_sizes`
  MODIFY `order_item_size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `product_addons`
--
ALTER TABLE `product_addons`
  MODIFY `addon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `product_color`
--
ALTER TABLE `product_color`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `product_size`
--
ALTER TABLE `product_size`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `product_variant_options`
--
ALTER TABLE `product_variant_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_walkincustomer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderitems_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_item_addons`
--
ALTER TABLE `order_item_addons`
  ADD CONSTRAINT `fk_itemaddons_addon` FOREIGN KEY (`addon_id`) REFERENCES `product_addons` (`addon_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_itemaddons_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_item_sizes`
--
ALTER TABLE `order_item_sizes`
  ADD CONSTRAINT `fk_itemsizes_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`category_id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_variant_options`
--
ALTER TABLE `product_variant_options`
  ADD CONSTRAINT `product_variant_options_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`),
  ADD CONSTRAINT `product_variant_options_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `product_size` (`size_id`),
  ADD CONSTRAINT `product_variant_options_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `product_color` (`color_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
