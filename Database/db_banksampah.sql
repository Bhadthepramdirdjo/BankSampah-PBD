-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 11:14 AM
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
-- Database: `db_banksampah`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(50) NOT NULL,
  `satuan` varchar(20) DEFAULT 'kg',
  `harga_per_satuan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_sampah`
--

INSERT INTO `jenis_sampah` (`id_jenis`, `nama_jenis`, `satuan`, `harga_per_satuan`) VALUES
(1, 'Plastik Botol', 'kg', 3000.00),
(2, 'Kardus Bekas', 'kg', 2000.00),
(3, 'Kaleng Aluminium', 'kg', 15000.00),
(4, 'Besi Tua', 'kg', 5000.00),
(5, 'Kertas Bekas', 'kg', 1500.00),
(9, 'Sampah Basah', 'kg', 5000.00),
(10, 'Kain bekas', 'kg', 7000.00);

-- --------------------------------------------------------

--
-- Table structure for table `nasabah`
--

CREATE TABLE `nasabah` (
  `id_nasabah` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_gabung` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nasabah`
--

INSERT INTO `nasabah` (`id_nasabah`, `nama_lengkap`, `nomor_telepon`, `alamat`, `tanggal_gabung`) VALUES
(2, 'Dimas Batagor', '081345678901', 'Jl. Kebon Jeruk No. 5', '2026-01-27'),
(11, 'Udin Petot', '081298765432', 'Gg. Mawar No. 3', '2026-01-28'),
(12, 'Willy Kurupuk', '081298765432', 'Gg. Mawar No. 3', '2026-01-28'),
(13, 'Rudi Kapur Ajaib', '081987654321', 'Komp. Buana Indah Blok A', '2026-01-28'),
(14, 'amet rujak', '081234567890', 'Jl. DPR No. 10', '2026-01-28'),
(15, 'Yoga Anjing', '084759841258', 'Jln. Kenangan No.22', '2026-01-28'),
(17, 'Bagas Cikuray', '087456981236', 'Jl. Mimpi No. 12', '2026-01-28'),
(19, 'Bhadriko Theo Pramudya', '085478965412', 'Jl. Wauk No 33', '2026-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `setoran`
--

CREATE TABLE `setoran` (
  `id_setoran` int(11) NOT NULL,
  `id_nasabah` int(11) DEFAULT NULL,
  `id_jenis` int(11) DEFAULT NULL,
  `berat` decimal(10,2) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `tanggal_setor` datetime DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setoran`
--

INSERT INTO `setoran` (`id_setoran`, `id_nasabah`, `id_jenis`, `berat`, `total_harga`, `tanggal_setor`, `catatan`) VALUES
(12, 11, 5, 38.00, 57000.00, '2026-01-28 11:37:16', 'Kertas Data Korupsi'),
(13, 11, 3, 15.00, 225000.00, '2026-01-28 11:38:41', 'Kaleng udah remuk, tapi masih bisa digunakan.'),
(14, 2, 1, 23.00, 69000.00, '2026-01-28 11:39:19', ''),
(16, 2, 4, 5.00, 25000.00, '2026-01-28 11:56:51', 'Bekas Sepeda'),
(34, 19, 2, 15.00, 30000.00, '2026-01-29 12:54:59', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id_nasabah`);

--
-- Indexes for table `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`id_setoran`),
  ADD KEY `id_nasabah` (`id_nasabah`),
  ADD KEY `id_jenis` (`id_jenis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id_nasabah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `setoran`
--
ALTER TABLE `setoran`
  MODIFY `id_setoran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `setoran`
--
ALTER TABLE `setoran`
  ADD CONSTRAINT `setoran_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`) ON DELETE CASCADE,
  ADD CONSTRAINT `setoran_ibfk_2` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_sampah` (`id_jenis`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
