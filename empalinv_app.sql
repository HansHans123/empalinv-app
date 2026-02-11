-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2026 at 12:25 PM
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
-- Database: `empalinv_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahan_baku`
--

CREATE TABLE `bahan_baku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_bahan` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `stok` decimal(10,2) NOT NULL DEFAULT 0.00,
  `satuan` varchar(255) NOT NULL,
  `stok_minimum` decimal(10,2) NOT NULL DEFAULT 0.00,
  `harga_beli` decimal(12,2) NOT NULL DEFAULT 0.00,
  `kategori` enum('daging','santan','rempah','bumbu','lainnya') NOT NULL DEFAULT 'lainnya',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahan_baku`
--

INSERT INTO `bahan_baku` (`id`, `kode_bahan`, `nama`, `stok`, `satuan`, `stok_minimum`, `harga_beli`, `kategori`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BB001', 'Daging Sapi', 30.00, 'kg', 10.00, 120000.00, 'daging', 'aktif', '2026-02-11 01:09:54', '2026-02-11 04:17:58', NULL),
(2, 'BB002', 'Santan Kelapa', 24.65, 'liter', 5.00, 15000.00, 'santan', 'aktif', '2026-02-11 01:09:54', '2026-02-11 02:53:47', NULL),
(3, 'BB003', 'Kunyit', 3.25, 'kg', 0.50, 35000.00, 'rempah', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(4, 'BB004', 'Serai', 40.00, 'batang', 10.00, 2000.00, 'bumbu', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(5, 'BB005', 'Daun Salam', 0.80, 'kg', 0.10, 50000.00, 'bumbu', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(6, 'BB006', 'Lengkuas', 5.00, 'kg', 1.00, 25000.00, 'bumbu', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(7, 'BB007', 'Jahe', 4.50, 'kg', 1.00, 30000.00, 'rempah', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(8, 'BB008', 'Bawang Merah', 15.00, 'kg', 3.00, 40000.00, 'bumbu', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(9, 'BB009', 'Bawang Putih', 10.00, 'kg', 2.00, 45000.00, 'bumbu', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(10, 'BB010', 'Garam', 8.00, 'kg', 2.00, 10000.00, 'bumbu', 'aktif', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(11, 'PR123', 'Paprika', 16.00, 'kg', 15.00, 20000.00, 'rempah', 'aktif', '2026-02-11 02:09:22', '2026-02-11 02:10:02', '2026-02-11 02:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penjualan_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_porsi` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id`, `penjualan_id`, `menu_id`, `jumlah_porsi`, `harga_satuan`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 3000.00, 3000.00, '2026-02-11 02:53:24', '2026-02-11 02:53:24'),
(2, 1, 3, 1, 45000.00, 45000.00, '2026-02-11 02:53:24', '2026-02-11 02:53:24'),
(3, 1, 6, 1, 7000.00, 7000.00, '2026-02-11 02:53:24', '2026-02-11 02:53:24'),
(4, 2, 5, 1, 3000.00, 3000.00, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(5, 2, 4, 1, 5000.00, 5000.00, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(6, 2, 2, 1, 35000.00, 35000.00, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(7, 3, 3, 1, 45000.00, 45000.00, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(8, 3, 1, 1, 25000.00, 25000.00, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(9, 3, 2, 1, 35000.00, 35000.00, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(10, 4, 5, 1, 3000.00, 3000.00, '2026-02-11 02:59:55', '2026-02-11 02:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_id`, `aktivitas`, `deskripsi`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'Login', 'Admin login ke sistem', '192.168.1.100', 'Mozilla/5.0', '2026-02-11 01:09:54', NULL),
(2, 1, 'Tambah Bahan', 'Menambahkan bahan baku baru: Daging Sapi', '192.168.1.100', 'Mozilla/5.0', '2026-02-11 01:09:54', NULL),
(3, 2, 'Transaksi', 'Melakukan transaksi penjualan pertama', '192.168.1.101', 'Mozilla/5.0', '2026-02-11 01:09:54', NULL),
(4, 3, 'Cek Stok', 'Melakukan pengecekan stok fisik', '192.168.1.102', 'Mozilla/5.0', '2026-02-11 01:09:54', NULL),
(5, 1, 'Menambah Bahan Baku', 'Menambah bahan baku baru: Paprika (PR123)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:09:22', '2026-02-11 02:09:22'),
(6, 1, 'Update Stok', 'masuk bahan Paprika sebanyak 10 kg', NULL, NULL, '2026-02-11 02:09:51', '2026-02-11 02:09:51'),
(7, 1, 'Update Stok Manual', 'Menambah stok Paprika sebanyak 10 kg - reja baru beli', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:09:51', '2026-02-11 02:09:51'),
(8, 1, 'Menghapus Bahan Baku', 'Menghapus bahan baku: Paprika (PR123)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:10:02', '2026-02-11 02:10:02'),
(9, 1, 'Menambah Menu', 'Menambah menu baru: Ayam Kuah Porsi Reja (MR001)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:35:35', '2026-02-11 02:35:35'),
(10, 1, 'Menambah Resep', 'Menambah bahan Bawang Merah ke menu Ayam Kuah Porsi Reja', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:36:02', '2026-02-11 02:36:02'),
(11, 1, 'Menambah Resep', 'Menambah bahan Garam ke menu Ayam Kuah Porsi Reja', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:36:17', '2026-02-11 02:36:17'),
(12, 1, 'Mengedit Menu', 'Mengedit menu: Ayam Kuah Porsi Reja (MR001)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:36:31', '2026-02-11 02:36:31'),
(13, 1, 'Mengedit Menu', 'Mengedit menu: Ayam Kuah Porsi Reja (MR001)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:37:05', '2026-02-11 02:37:05'),
(14, 1, 'Menghapus Menu', 'Menghapus menu: Ayam Kuah Porsi Reja (MR001)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:37:09', '2026-02-11 02:37:09'),
(15, 2, 'Transaksi Baru', 'Membuat transaksi TRX-20260211-0001 senilai Rp 55.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:53:24', '2026-02-11 02:53:24'),
(16, 2, 'Transaksi Penjualan', 'Melakukan transaksi TRX-20260211-0001 dengan total Rp 55.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:53:24', '2026-02-11 02:53:24'),
(17, 2, 'Transaksi Baru', 'Membuat transaksi TRX-20260211-0002 senilai Rp 43.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(18, 2, 'Update Stok', 'keluar bahan Daging Sapi sebanyak 0.35 kg', NULL, NULL, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(19, 2, 'Update Stok', 'keluar bahan Santan Kelapa sebanyak 0.2 liter', NULL, NULL, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(20, 2, 'Update Stok', 'keluar bahan Daging Sapi sebanyak 0.35 kg', NULL, NULL, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(21, 2, 'Update Stok', 'keluar bahan Santan Kelapa sebanyak 0.2 liter', NULL, NULL, '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(22, 2, 'Transaksi Penjualan', 'Melakukan transaksi TRX-20260211-0002 dengan total Rp 43.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:53:40', '2026-02-11 02:53:40'),
(23, 2, 'Transaksi Baru', 'Membuat transaksi TRX-20260211-0003 senilai Rp 105.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(24, 2, 'Update Stok', 'keluar bahan Daging Sapi sebanyak 0.25 kg', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(25, 2, 'Update Stok', 'keluar bahan Santan Kelapa sebanyak 0.15 liter', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(26, 2, 'Update Stok', 'keluar bahan Daging Sapi sebanyak 0.25 kg', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(27, 2, 'Update Stok', 'keluar bahan Santan Kelapa sebanyak 0.15 liter', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(28, 2, 'Update Stok', 'keluar bahan Daging Sapi sebanyak 0.35 kg', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(29, 2, 'Update Stok', 'keluar bahan Santan Kelapa sebanyak 0.2 liter', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(30, 2, 'Update Stok', 'keluar bahan Daging Sapi sebanyak 0.35 kg', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(31, 2, 'Update Stok', 'keluar bahan Santan Kelapa sebanyak 0.2 liter', NULL, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(32, 2, 'Transaksi Penjualan', 'Melakukan transaksi TRX-20260211-0003 dengan total Rp 105.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:53:47', '2026-02-11 02:53:47'),
(33, 2, 'Transaksi Baru', 'Membuat transaksi TRX-20260211-0004 senilai Rp 3.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:59:55', '2026-02-11 02:59:55'),
(34, 2, 'Transaksi Penjualan', 'Melakukan transaksi TRX-20260211-0004 dengan total Rp 3.000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 02:59:55', '2026-02-11 02:59:55'),
(35, 3, 'Pengecekan Stok Fisik', 'Melakukan pengecekan stok fisik Daging Sapi. Selisih: -18.60 kg (38.27%)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 04:17:57', '2026-02-11 04:17:57'),
(36, 3, 'Opname Stok', 'Stok fisik Daging Sapi dicatat: 30 kg (sebelumnya 48.60), selisih -18.60 (38.271604938272%)', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-11 04:17:58', '2026-02-11 04:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_menu` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `kode_menu`, `nama`, `harga`, `deskripsi`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'M001', 'Empal Gentong Porsi Reguler', 25000.00, 'Empal gentong dengan porsi reguler, cocok untuk 1 orang', 'tersedia', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(2, 'M002', 'Empal Gentong Porsi Besar', 35000.00, 'Empal gentong dengan porsi besar, tambah daging dan kuah', 'tersedia', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(3, 'M003', 'Empal Gentong Spesial', 45000.00, 'Empal gentong dengan daging pilihan dan rempah lengkap', 'tersedia', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(4, 'M004', 'Nasi Putih', 5000.00, 'Nasi putih hangat', 'tersedia', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(5, 'M005', 'Teh Manis', 3000.00, 'Teh manis dingin/hangat', 'tersedia', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(6, 'M006', 'Jeruk Peras', 7000.00, 'Jeruk peras asli', 'tersedia', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL),
(7, 'MR001', 'Ayam Kuah Porsi Reja', 30000.00, 'ini menu porsi reja', 'tersedia', '2026-02-11 02:35:35', '2026-02-11 02:37:09', '2026-02-11 02:37:09');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_02_11_075902_create_users_table', 1),
(2, '2026_02_11_075912_create_bahan_baku_table', 1),
(3, '2026_02_11_075922_create_menu_table', 1),
(4, '2026_02_11_075931_create_resep_table', 1),
(5, '2026_02_11_075940_create_penjualan_table', 1),
(6, '2026_02_11_075952_create_detail_penjualan_table', 1),
(7, '2026_02_11_080000_create_pembelian_bahan_baku_table', 1),
(8, '2026_02_11_080008_create_pemakaian_bahan_baku_table', 1),
(9, '2026_02_11_080018_create_stok_fisik_table', 1),
(10, '2026_02_11_080027_create_log_aktivitas_table', 1),
(11, '2026_02_11_082313_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pemakaian_bahan_baku`
--

CREATE TABLE `pemakaian_bahan_baku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_pakai` decimal(10,2) NOT NULL,
  `stok_awal` decimal(10,2) NOT NULL,
  `stok_akhir` decimal(10,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_bahan_baku`
--

CREATE TABLE `pembelian_bahan_baku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pembelian` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembelian_bahan_baku`
--

INSERT INTO `pembelian_bahan_baku` (`id`, `kode_pembelian`, `tanggal`, `bahan_id`, `jumlah`, `harga_satuan`, `total`, `supplier`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'PB-001', '2026-02-10 08:09:54', 1, 50.00, 120000.00, 6000000.00, 'Supplier Daging Sapi', 1, '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(2, 'PB-002', '2026-02-10 08:09:54', 2, 30.00, 15000.00, 450000.00, 'Supplier Santan', 1, '2026-02-11 01:09:54', '2026-02-11 01:09:54');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_transaksi` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `jumlah_porsi` int(11) NOT NULL DEFAULT 0,
  `pembayaran` enum('tunai','debit','qris','lainnya') NOT NULL DEFAULT 'tunai',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `kode_transaksi`, `tanggal`, `total`, `jumlah_porsi`, `pembayaran`, `user_id`, `catatan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TRX-20260211-0001', '2026-02-11 09:53:23', 55000.00, 3, 'tunai', 2, NULL, '2026-02-11 02:53:23', '2026-02-11 02:53:23', NULL),
(2, 'TRX-20260211-0002', '2026-02-11 09:53:40', 43000.00, 3, 'debit', 2, NULL, '2026-02-11 02:53:40', '2026-02-11 02:53:40', NULL),
(3, 'TRX-20260211-0003', '2026-02-11 09:53:47', 105000.00, 3, 'qris', 2, NULL, '2026-02-11 02:53:47', '2026-02-11 02:53:47', NULL),
(4, 'TRX-20260211-0004', '2026-02-11 09:59:55', 3000.00, 1, 'tunai', 2, NULL, '2026-02-11 02:59:55', '2026-02-11 02:59:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resep`
--

INSERT INTO `resep` (`id`, `menu_id`, `bahan_id`, `jumlah`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0.25, 'kg', '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(2, 1, 2, 0.15, 'liter', '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(3, 2, 1, 0.35, 'kg', '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(4, 2, 2, 0.20, 'liter', '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(5, 7, 8, 1.00, 'kg', '2026-02-11 02:36:02', '2026-02-11 02:36:02'),
(6, 7, 10, 2.00, 'g', '2026-02-11 02:36:17', '2026-02-11 02:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('GVcdFVbXa3Ju7tulG0uU619RqSVKSo8lC7LJvpUX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielRPajlvQTZYN1N6MFJnQk9rNlhBdmZuemV2d1pOZWZTdXVWdkF6eCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1770808717);

-- --------------------------------------------------------

--
-- Table structure for table `stok_fisik`
--

CREATE TABLE `stok_fisik` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `bahan_id` bigint(20) UNSIGNED NOT NULL,
  `stok_sistem` decimal(10,2) NOT NULL,
  `stok_fisik` decimal(10,2) NOT NULL,
  `selisih` decimal(10,2) NOT NULL,
  `persentase_selisih` decimal(5,2) NOT NULL,
  `status` enum('normal','melebihi_toleransi') NOT NULL DEFAULT 'normal',
  `keterangan` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_fisik`
--

INSERT INTO `stok_fisik` (`id`, `tanggal`, `bahan_id`, `stok_sistem`, `stok_fisik`, `selisih`, `persentase_selisih`, `status`, `keterangan`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '2026-02-10', 1, 52.50, 50.50, -2.00, 3.81, 'melebihi_toleransi', 'Selisih lebih dari 5%', 3, '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(2, '2026-02-10', 2, 28.75, 25.75, -3.00, 10.43, 'melebihi_toleransi', 'Penguapan saat memasak', 3, '2026-02-11 01:09:54', '2026-02-11 01:09:54'),
(3, '2026-02-11', 1, 48.60, 30.00, -18.60, 38.27, 'melebihi_toleransi', NULL, 3, '2026-02-11 04:17:57', '2026-02-11 04:17:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','staf_dapur') NOT NULL DEFAULT 'kasir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin Empal Gentong', 'admin', '$2y$12$xAVsC8QSoHyKKLAWGM0lFu9FBRCV5m5sh.GlmAzqF214UXuKnh7zO', 'admin', '2026-02-11 01:09:53', '2026-02-11 01:09:53', NULL),
(2, 'Kasir 1', 'kasir1', '$2y$12$17rK9P4uuocSFLwhmtTaRewP0WJUGr.eHf1y/2Ik2k3fE2e/4UF0q', 'kasir', '2026-02-11 01:09:53', '2026-02-11 01:09:53', NULL),
(3, 'Staf Dapur', 'dapur1', '$2y$12$z7p6KCFcG6p34dhNd85Oyu6Hu7i47SSMO93tglGepaBQW5vbPGVRy', 'staf_dapur', '2026-02-11 01:09:54', '2026-02-11 01:09:54', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bahan_baku_kode_bahan_unique` (`kode_bahan`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_penjualan_penjualan_id_foreign` (`penjualan_id`),
  ADD KEY `detail_penjualan_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_aktivitas_user_id_foreign` (`user_id`),
  ADD KEY `log_aktivitas_created_at_index` (`created_at`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_kode_menu_unique` (`kode_menu`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemakaian_bahan_baku`
--
ALTER TABLE `pemakaian_bahan_baku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemakaian_bahan_baku_bahan_id_foreign` (`bahan_id`),
  ADD KEY `pemakaian_bahan_baku_user_id_foreign` (`user_id`),
  ADD KEY `pemakaian_bahan_baku_tanggal_bahan_id_index` (`tanggal`,`bahan_id`);

--
-- Indexes for table `pembelian_bahan_baku`
--
ALTER TABLE `pembelian_bahan_baku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembelian_bahan_baku_kode_pembelian_unique` (`kode_pembelian`),
  ADD KEY `pembelian_bahan_baku_bahan_id_foreign` (`bahan_id`),
  ADD KEY `pembelian_bahan_baku_user_id_foreign` (`user_id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penjualan_kode_transaksi_unique` (`kode_transaksi`),
  ADD KEY `penjualan_user_id_foreign` (`user_id`),
  ADD KEY `penjualan_tanggal_index` (`tanggal`),
  ADD KEY `penjualan_kode_transaksi_index` (`kode_transaksi`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resep_menu_id_bahan_id_unique` (`menu_id`,`bahan_id`),
  ADD KEY `resep_bahan_id_foreign` (`bahan_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stok_fisik`
--
ALTER TABLE `stok_fisik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stok_fisik_tanggal_bahan_id_unique` (`tanggal`,`bahan_id`),
  ADD KEY `stok_fisik_bahan_id_foreign` (`bahan_id`),
  ADD KEY `stok_fisik_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan_baku`
--
ALTER TABLE `bahan_baku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pemakaian_bahan_baku`
--
ALTER TABLE `pemakaian_bahan_baku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian_bahan_baku`
--
ALTER TABLE `pembelian_bahan_baku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stok_fisik`
--
ALTER TABLE `stok_fisik`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penjualan_penjualan_id_foreign` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemakaian_bahan_baku`
--
ALTER TABLE `pemakaian_bahan_baku`
  ADD CONSTRAINT `pemakaian_bahan_baku_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahan_baku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemakaian_bahan_baku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembelian_bahan_baku`
--
ALTER TABLE `pembelian_bahan_baku`
  ADD CONSTRAINT `pembelian_bahan_baku_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahan_baku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembelian_bahan_baku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resep`
--
ALTER TABLE `resep`
  ADD CONSTRAINT `resep_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahan_baku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resep_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok_fisik`
--
ALTER TABLE `stok_fisik`
  ADD CONSTRAINT `stok_fisik_bahan_id_foreign` FOREIGN KEY (`bahan_id`) REFERENCES `bahan_baku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stok_fisik_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
