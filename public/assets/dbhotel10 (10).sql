-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2025 at 05:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbhotel10`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `no_hp_admin` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `no_hp_admin`, `email`, `password`) VALUES
(1, 'Admin Utama', '081200000001', 'admin.utama@hotel10.com', '1702a132e761560b6183a37213518a27'),
(2, 'Manager Hotel', '081200000002', 'manager.hotel@hotel10.com', '1702a132e761560b6183a37213518a27'),
(3, 'man', '14147', 'man@mail.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `detail_reservasi_kamar`
--

CREATE TABLE `detail_reservasi_kamar` (
  `id_detail_reservasi` int(11) NOT NULL,
  `jumlah_malam_kamar` int(11) DEFAULT NULL,
  `id_reservasi` int(11) DEFAULT NULL,
  `id_kamar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_reservasi_kamar`
--

INSERT INTO `detail_reservasi_kamar` (`id_detail_reservasi`, `jumlah_malam_kamar`, `id_reservasi`, `id_kamar`) VALUES
(1, 2, 4, 7),
(2, 3, 5, 9),
(3, 8, 6, 10),
(17, 1, 20, 7),
(19, 1, 22, 9),
(21, 1, 24, 7),
(22, 1, 25, 7),
(24, 1, 27, 7),
(25, 1, 28, 7),
(26, 1, 29, 7),
(27, 1, 30, 10),
(28, 15, 31, 8),
(29, 49, 32, 8),
(30, 5, 33, 7),
(31, 2, 34, 7);

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `harga_kamar` decimal(10,2) DEFAULT NULL,
  `tipe_kamar` varchar(50) DEFAULT NULL,
  `jenis_ranjang` varchar(50) DEFAULT NULL,
  `jumlah_tamu` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `nomor_kamar` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `harga_kamar`, `tipe_kamar`, `jenis_ranjang`, `jumlah_tamu`, `foto`, `deskripsi`, `nomor_kamar`) VALUES
(7, 1400000.00, 'DELUXE ', '1 King Bed', 3, '1752832772_396c0a59069d63ac633c.jpg', 'Kamar nyaman dengan segala fasilitas yang mewah dan pemandangan yang indahyrhrrh', '301'),
(8, 1000000.00, 'VIP ', '1 QueenBed', 3, '1752832840_9e29f82ea5fa9066dd2a.jpg', 'Kamar nyaman dengan fasilitas mewah dan pemandangan indah ', '201'),
(9, 900000.00, 'Regular', '1 Queen bed', 2, '1753151757_420f725ad850a9e4a53f.png', 'Kamar regular dengan kelengkapan aminities', '301-320'),
(10, 123.00, 'kamar mayat', '10 Kasur Kematian', 12, '1753242206_44419c865fc10177ba34.png', 'fsfef', '725');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(11) NOT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `total_harga_reservasi` decimal(12,2) DEFAULT NULL,
  `id_tamu` int(11) DEFAULT NULL,
  `status` enum('Reserved','Check-In','selesai','Dibatalkan') NOT NULL,
  `status_pembayaran` varchar(20) NOT NULL DEFAULT 'pending',
  `midtrans_order_id` varchar(255) DEFAULT NULL,
  `snap_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `tgl_masuk`, `tgl_keluar`, `total_harga_reservasi`, `id_tamu`, `status`, `status_pembayaran`, `midtrans_order_id`, `snap_token`) VALUES
(4, '2025-07-23', '2025-07-25', 2800000.00, 5, 'Reserved', 'pending', NULL, NULL),
(5, '2025-07-23', '2025-07-26', 2700000.00, 5, 'Reserved', 'pending', NULL, NULL),
(6, '2025-07-23', '2025-07-31', 984.00, 5, 'Reserved', 'pending', NULL, NULL),
(20, '2025-08-01', '2025-08-02', 1400000.00, 5, 'Reserved', 'pending', 'HOTEL10-1753250112-7', '80a4f2e1-abf8-455b-b079-a1668f3acc38'),
(22, '2025-08-03', '2025-08-04', 900000.00, 5, 'Reserved', 'pending', 'HOTEL10-1753250988-9', '8cfac646-f6ae-4085-be8a-7faf4a17a771'),
(24, '2025-08-05', '2025-08-06', 1400000.00, 5, 'Reserved', 'settlement', 'HOTEL10-1753251204-7', '522a5876-9245-4518-8448-dfd989b68029'),
(25, '2025-08-04', '2025-08-05', 1400000.00, 5, 'Reserved', 'pending', 'HOTEL10-1753251333-7', '236363aa-663d-4849-bf84-4a56fe765951'),
(27, '2025-08-06', '2025-08-07', 1400000.00, 5, 'Check-In', 'settlement', 'HOTEL10-1753252649-7', 'a85f1110-8cda-4701-8b2c-9df4aaf10eb3'),
(28, '2025-08-07', '2025-08-08', 1400000.00, 5, 'Dibatalkan', 'pending', 'HOTEL10-1753252729-7', 'e62a5d2f-cd9d-4e10-9887-181a16f5f552'),
(29, '2025-08-07', '2025-08-08', 1400000.00, 5, 'selesai', 'settlement', 'HOTEL10-1753252748-7', 'dc31f113-bfbe-49bb-b27b-4760cbdc1d8d'),
(30, '2025-07-31', '2025-08-01', 123.00, 5, 'Dibatalkan', 'pending', 'HOTEL10-1753254040-10', 'ad3dcfe0-f258-469f-a6b1-7620c0f89935'),
(31, '2025-07-23', '2025-08-07', 15000000.00, 5, 'Dibatalkan', 'pending', 'HOTEL10-1753256255-8', '48cb7096-6de0-4ed7-b978-6d2d25114f0f'),
(32, '2025-07-23', '2025-09-10', 49000000.00, 6, 'Dibatalkan', 'pending', 'HOTEL10-1753263657-8', '63c2eb35-1d80-474d-bd8c-bd76fc1511ec'),
(33, '2025-07-23', '2025-07-28', 7000000.00, 6, 'Dibatalkan', 'pending', 'HOTEL10-1753263702-7', '6e3fc519-8a34-4575-ad0e-61fff2b31a81'),
(34, '2025-07-24', '2025-07-26', 2800000.00, 6, 'Check-In', 'settlement', 'HOTEL10-1753263725-7', '7742e1f4-26a7-45b4-8a8e-c336dfd4519c');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `id_riwayat` int(11) NOT NULL,
  `nama_tamu` varchar(100) DEFAULT NULL,
  `tipe_kamar` varchar(50) DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `total_bayar` decimal(12,2) DEFAULT NULL,
  `tgl_penyelesaian` datetime NOT NULL,
  `status_saat_selesai` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat`
--

INSERT INTO `riwayat` (`id_riwayat`, `nama_tamu`, `tipe_kamar`, `tgl_masuk`, `tgl_keluar`, `total_bayar`, `tgl_penyelesaian`, `status_saat_selesai`) VALUES
(1, 'satria ', 'DELUXE ', '2025-08-07', '2025-08-08', 1400000.00, '2025-07-23 07:00:00', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `tamu`
--

CREATE TABLE `tamu` (
  `id_tamu` int(11) NOT NULL,
  `nama_tamu` varchar(100) DEFAULT NULL,
  `no_hp_tamu` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tamu`
--

INSERT INTO `tamu` (`id_tamu`, `nama_tamu`, `no_hp_tamu`, `email`, `password`) VALUES
(1, 'kontolodon', '081234567890', 'u1@m.com', '$2y$10$iM8JYWNr0tugKbQjvtjHcObab63iraYY6LYoQunwHW0w/K01iOXU2'),
(2, 'Tamu Uji Dua', '089876543210', 'tamu2@mail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(5, 'satria ', '086567876565', 'satria@mail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(6, 'aurel', '1040174012', 'rel@mail.com', 'f5bb0c8de146c67b44babbf4e6584cc0'),
(8, 'kila', '1234567899', 'kila@mail.com', 'bb2d91d0fbbebe8719509ed0f865c63f');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `detail_reservasi_kamar`
--
ALTER TABLE `detail_reservasi_kamar`
  ADD PRIMARY KEY (`id_detail_reservasi`),
  ADD KEY `fk_reservasi` (`id_reservasi`),
  ADD KEY `fk_detail_reservasi_kamar` (`id_kamar`);

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `fk_tamu` (`id_tamu`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id_riwayat`);

--
-- Indexes for table `tamu`
--
ALTER TABLE `tamu`
  ADD PRIMARY KEY (`id_tamu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_reservasi_kamar`
--
ALTER TABLE `detail_reservasi_kamar`
  MODIFY `id_detail_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tamu`
--
ALTER TABLE `tamu`
  MODIFY `id_tamu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_reservasi_kamar`
--
ALTER TABLE `detail_reservasi_kamar`
  ADD CONSTRAINT `detail_reservasi_kamar_ibfk_1` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasi` (`id_reservasi`),
  ADD CONSTRAINT `fk_detail_reservasi_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`id_tamu`) REFERENCES `tamu` (`id_tamu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
