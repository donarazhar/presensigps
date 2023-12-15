-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2023 at 10:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensigps`
--

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `kode_dept` char(3) NOT NULL,
  `nama_dept` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`kode_dept`, `nama_dept`) VALUES
('hrd', 'human resource development'),
('it', 'Information Tech'),
('mkt', 'marketing');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` char(5) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `jabatan` varchar(20) DEFAULT NULL,
  `no_hp` varchar(13) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `foto` varchar(30) DEFAULT NULL,
  `kode_dept` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `jabatan`, `no_hp`, `password`, `remember_token`, `foto`, `kode_dept`) VALUES
('1234', 'Donarsi Azhar', 'Head of DAL Army', '088214740182', '$2y$12$vX7Y4yhjQUSVdPPMvCkh4.FQphXa5sMPoHFjsPeqxlkEcu2T0aQKu', NULL, '1234.jpg', 'mkt'),
('1235', 'Puspita Suswanti', 'Manajer Keuangan', '088214740182', '$2y$12$Fw1pQfLZu1NwR7O80fpFHe1NifxiUU2Y8bsq7sYIPdJvAyKcCDApW', NULL, '1235.jpg', 'hrd'),
('1236', 'Agus Soni', 'Bagian Akuntansi', '088214740182', '$2y$12$vX7Y4yhjQUSVdPPMvCkh4.FQphXa5sMPoHFjsPeqxlkEcu2T0aQKu', NULL, '1236.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id` int(11) NOT NULL,
  `nik` char(5) DEFAULT NULL,
  `tgl_izin` date DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status_approved` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`id`, `nik`, `tgl_izin`, `status`, `keterangan`, `status_approved`) VALUES
(1, '1235', '2023-12-01', 'i', 'Mengantarkan anak', '0'),
(5, '1234', '2023-12-28', 's', 'Sakit perut', '2'),
(10, '1234', '2023-12-29', 'i', 'Pergi kepasa', '1'),
(11, '1234', '2023-12-27', 's', 'Sakit perut', '1'),
(12, '1234', '2023-12-26', 'i', 'Menjenguk Kakek', '0'),
(13, '1234', '2023-12-13', 'i', 'Tidak masuk kerja', '1'),
(14, '1236', '2023-12-13', 's', 'Sakit perut', '1');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `nik` char(5) DEFAULT NULL,
  `tgl_presensi` date DEFAULT NULL,
  `jam_in` time DEFAULT NULL,
  `jam_out` time DEFAULT NULL,
  `foto_in` varchar(255) DEFAULT NULL,
  `foto_out` varchar(255) DEFAULT NULL,
  `lokasi_in` text DEFAULT NULL,
  `lokasi_out` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tgl_presensi`, `jam_in`, `jam_out`, `foto_in`, `foto_out`, `lokasi_in`, `lokasi_out`) VALUES
(33, '1234', '2023-12-10', '12:43:53', '12:47:16', '1234_2023-12-10_in.png', '1234_2023-12-10_out.png', '-6.2350738,106.7991351', '-6.2350635,106.7991294'),
(34, '1234', '2023-12-11', '08:25:10', '18:00:21', '1234_2023-12-11_in.png', '1234_2023-12-11_out.png', '-6.235218499999999,106.79933750000001', '-6.243176999999999,106.750999'),
(35, '1235', '2023-12-11', '09:54:23', NULL, '1235_2023-12-11_in.png', NULL, '-6.2350559,106.7991266', NULL),
(36, '1236', '2023-12-11', '12:36:09', '14:29:22', '1236_2023-12-11_in.png', '1236_2023-12-11_out.png', '-6.2350559,106.7991266', '-6.235067,106.7991335'),
(37, '1234', '2023-12-13', '12:18:07', '14:57:55', '1234_2023-12-13_in.png', '1234_2023-12-13_out.png', '-6.2350592,106.7991279', '-6.2350566,106.7991284'),
(38, '1235', '2023-12-13', '14:58:23', '14:58:31', '1235_2023-12-13_in.png', '1235_2023-12-13_out.png', '-6.2350566,106.7991284', '-6.2350566,106.7991284'),
(39, '1236', '2023-12-13', '14:58:50', '14:58:58', '1236_2023-12-13_in.png', '1236_2023-12-13_out.png', '-6.2350566,106.7991284', '-6.2350566,106.7991284');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Donarsi Yosianto', 'donarazhar@gmail.com', NULL, '$2y$12$vX7Y4yhjQUSVdPPMvCkh4.FQphXa5sMPoHFjsPeqxlkEcu2T0aQKu', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_dept`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
