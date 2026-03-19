-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 08:50 PM
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
-- Database: `sesi8_bootcampeduwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `description`) VALUES
(1, 'Laptop Asus VivoBook', 8500000.00, 'Elektronik', 'Laptop ringan untuk coding dan kerja'),
(2, 'Laptop Lenovo IdeaPad', 7200000.00, 'Elektronik', 'Laptop mid-range performa stabil'),
(3, 'Mouse Logitech MX', 450000.00, 'Elektronik', 'Mouse wireless ergonomis presisi tinggi'),
(4, 'Mouse Rexus Gaming', 185000.00, 'Elektronik', 'Mouse gaming DPI tinggi RGB'),
(5, 'Keyboard Mechanical TKL', 650000.00, 'Elektronik', 'Keyboard mekanikal tenkeyless switch blue'),
(6, 'Headphone Sony WH', 1200000.00, 'Elektronik', 'Headphone noise cancelling premium'),
(7, 'Earbuds TWS Xiaomi', 280000.00, 'Elektronik', 'TWS earbuds bass kencang baterai tahan lama'),
(8, 'Monitor LG 24 inch', 2100000.00, 'Elektronik', 'Monitor IPS full HD anti glare'),
(9, 'Webcam Logitech C920', 850000.00, 'Elektronik', 'Webcam full HD untuk meeting online'),
(10, 'SSD Samsung 512GB', 750000.00, 'Elektronik', 'SSD SATA kecepatan baca 550MB/s'),
(11, 'Baju Polo Pria', 150000.00, 'Fashion', 'Polo shirt pria slim fit bahan lacoste'),
(12, 'Kemeja Flannel', 185000.00, 'Fashion', 'Kemeja flannel casual pria motif kotak'),
(13, 'Kaos Oversize', 95000.00, 'Fashion', 'Kaos oversize unisex cotton combed 30s'),
(14, 'Sepatu Nike Air Max', 1350000.00, 'Fashion', 'Sepatu running ringan sol tebal'),
(15, 'Sepatu Vans Old Skool', 890000.00, 'Fashion', 'Sneakers klasik kanvas hitam putih'),
(16, 'Celana Chino Slim', 220000.00, 'Fashion', 'Celana chino slim fit stretch nyaman'),
(17, 'Jaket Bomber', 320000.00, 'Fashion', 'Jaket bomber unisex bahan parasut'),
(18, 'Topi Baseball Cap', 75000.00, 'Fashion', 'Topi baseball polos adjustable'),
(19, 'Tas Ransel Laptop', 285000.00, 'Fashion', 'Ransel anti air slot laptop 15 inch'),
(20, 'Sandal Outdoor Eiger', 195000.00, 'Fashion', 'Sandal gunung kuat sol karet tebal'),
(21, 'Kopi Arabika Aceh', 85000.00, 'Makanan', 'Kopi single origin Gayo Aceh medium roast'),
(22, 'Kopi Robusta Toraja', 72000.00, 'Makanan', 'Kopi Toraja bold flavor low acid'),
(23, 'Teh Hijau Matcha', 65000.00, 'Makanan', 'Matcha powder grade premium Jepang'),
(24, 'Granola Oat Coklat', 55000.00, 'Makanan', 'Granola sereal oat coklat tanpa pengawet'),
(25, 'Madu Hutan Murni', 120000.00, 'Makanan', 'Madu hutan asli Kalimantan raw unfiltered'),
(26, 'Buku PHP Dasar', 120000.00, 'Buku', 'Panduan belajar PHP dari nol sampai mahir'),
(27, 'Buku Laravel Lengkap', 165000.00, 'Buku', 'Belajar Laravel 10 dari dasar hingga deploy'),
(28, 'Buku Clean Code', 210000.00, 'Buku', 'Prinsip menulis kode bersih ala Robert Martin'),
(29, 'Buku UI/UX Design', 145000.00, 'Buku', 'Panduan desain antarmuka yang intuitif'),
(30, 'Buku JavaScript Modern', 175000.00, 'Buku', 'ES6+ async await dan framework modern');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
