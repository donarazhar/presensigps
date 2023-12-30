-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Des 2023 pada 11.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

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
-- Struktur dari tabel `cabang`
--

CREATE TABLE `cabang` (
  `kode_cabang` char(3) NOT NULL,
  `nama_cabang` varchar(50) DEFAULT NULL,
  `lokasi_cabang` varchar(255) DEFAULT NULL,
  `radius_cabang` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cabang`
--

INSERT INTO `cabang` (`kode_cabang`, `nama_cabang`, `lokasi_cabang`, `radius_cabang`) VALUES
('msj', 'Kantor Takmir MAA', '-6.234991046709382,106.79916746810034', 50),
('pst', 'Kantor YPI Al Azhar Pusat', '-6.235205825517283,106.79938871108641', 50),
('rmh', 'Rumah Donar', '-6.243332037953215,106.75115675766764', 50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `departemen`
--

CREATE TABLE `departemen` (
  `kode_dept` char(10) NOT NULL,
  `nama_dept` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `departemen`
--

INSERT INTO `departemen` (`kode_dept`, `nama_dept`) VALUES
('hrd', 'Human Resource'),
('it', 'Information Technologi'),
('mkt', 'Marketing');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam_kerja`
--

CREATE TABLE `jam_kerja` (
  `kode_jam_kerja` char(4) NOT NULL,
  `nama_jam_kerja` varchar(15) NOT NULL,
  `awal_jam_masuk` time NOT NULL,
  `jam_masuk` time NOT NULL,
  `akhir_jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `lintashari` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jam_kerja`
--

INSERT INTO `jam_kerja` (`kode_jam_kerja`, `nama_jam_kerja`, `awal_jam_masuk`, `jam_masuk`, `akhir_jam_masuk`, `jam_pulang`, `lintashari`) VALUES
('jk01', 'Shift 1', '12:00:00', '13:55:00', '13:57:00', '13:58:00', '0'),
('jk02', 'Shift 2', '13:30:00', '13:40:00', '13:47:00', '13:48:00', '0'),
('jk03', 'Shift 3', '10:00:00', '11:30:00', '12:00:00', '23:00:00', '0'),
('jk04', 'Shift 4', '13:00:00', '19:20:00', '19:50:00', '23:00:00', '0'),
('jk05', 'Shift 5', '15:00:00', '22:00:00', '23:59:00', '07:00:00', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `nik` char(5) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `jabatan` varchar(20) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `foto` varchar(30) DEFAULT NULL,
  `kode_dept` char(10) DEFAULT NULL,
  `kode_cabang` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`nik`, `nama_lengkap`, `jabatan`, `no_hp`, `password`, `remember_token`, `foto`, `kode_dept`, `kode_cabang`) VALUES
('1232', 'Lala Dal Army', 'Karyawan PKL', '08811994872', '$2y$12$guozvJE3vcpkxuvBAQsS3O1zEaxDcsjC9xYqkXvMmy5d4iQ0yVXye', NULL, NULL, 'mkt', 'msj'),
('1233', 'Rizqi Azhar Kamil', 'Anak Tercinta', '08811994872', '$2y$12$68Au9Hug4h8R95pSiM7Wc.Anbf5g9GGRdld4V5swYVFS7BgC1wICO', NULL, '1233.jpg', 'it', 'msj'),
('1234', 'Donarsi Al Azhar', 'Head of DAL Army', '08811994872', '$2y$12$zjPvlUxq/OzAPhJQGoZ7P.K8CJVPhV3Ch5PDJNvzUAj1v9aamn7Zi', NULL, '1234.jpg', 'it', 'rmh'),
('1235', 'Puspita Suswanti', 'Staf TU', '08811994872', '$2y$12$ntOCw8V1e7RDVq/M9LbH4OOnjfDBliojt3jgrbTzJj2ebYQRi53v6', NULL, '1235.jpg', 'mkt', 'pst'),
('1236', 'Agus Soni', 'Bagian Akuntansi', '08811994872', '$2y$12$LX1vzq1UTS4da3Du5v8l8eegqrHJFSSYgQFCI2FPTGLf8i5mS6nva', NULL, '1236.png', 'mkt', 'rmh'),
('2222', 'Agung Jamal', 'Marbot Kebersihan', '08811994872', '$2y$12$q6C5pV3EthINHWci9oxdiO2k.30cFfSPX/fpim9RiD62jc.C.0Owe', NULL, NULL, 'mkt', 'msj');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_jamkerja`
--

CREATE TABLE `konfigurasi_jamkerja` (
  `nik` char(5) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `kode_jam_kerja` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konfigurasi_jamkerja`
--

INSERT INTO `konfigurasi_jamkerja` (`nik`, `hari`, `kode_jam_kerja`) VALUES
('1234', 'Senin', 'jk01'),
('1234', 'Selasa', 'jk01'),
('1234', 'Rabu', 'jk01'),
('1234', 'Kamis', 'jk01'),
('1234', 'Jumat', 'jk01'),
('1234', 'Sabtu', 'jk01'),
('1234', 'Minggu', 'jk01'),
('1233', 'Senin', 'jk01'),
('1233', 'Selasa', 'jk01'),
('1233', 'Rabu', 'jk01'),
('1233', 'Kamis', 'jk01'),
('1233', 'Jumat', 'jk01'),
('1233', 'Sabtu', 'jk01'),
('1233', 'Minggu', 'jk01'),
('1235', 'Senin', 'jk03'),
('1235', 'Selasa', 'jk03'),
('1235', 'Rabu', 'jk05'),
('1235', 'Kamis', 'jk03'),
('1235', 'Jumat', 'jk03'),
('1235', 'Sabtu', 'jk03'),
('1235', 'Minggu', 'jk03'),
('1236', 'Senin', 'jk02'),
('1236', 'Selasa', 'jk02'),
('1236', 'Rabu', 'jk05'),
('1236', 'Kamis', 'jk02'),
('1236', 'Jumat', 'jk02'),
('1236', 'Sabtu', 'jk02'),
('1236', 'Minggu', 'jk04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_jk_dept`
--

CREATE TABLE `konfigurasi_jk_dept` (
  `kode_jk_dept` char(10) NOT NULL,
  `kode_cabang` char(3) NOT NULL,
  `kode_dept` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konfigurasi_jk_dept`
--

INSERT INTO `konfigurasi_jk_dept` (`kode_jk_dept`, `kode_cabang`, `kode_dept`) VALUES
('jmsjhrd', 'msj', 'hrd'),
('jmsjmkt', 'msj', 'mkt'),
('jrmhhrd', 'rmh', 'hrd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_jk_dept_detail`
--

CREATE TABLE `konfigurasi_jk_dept_detail` (
  `kode_jk_dept` char(10) NOT NULL,
  `kode_jam_kerja` char(5) NOT NULL,
  `hari` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konfigurasi_jk_dept_detail`
--

INSERT INTO `konfigurasi_jk_dept_detail` (`kode_jk_dept`, `kode_jam_kerja`, `hari`) VALUES
('jmsjmkt', 'jk01', 'Senin'),
('jmsjmkt', 'jk01', 'Selasa'),
('jmsjmkt', 'jk01', 'Rabu'),
('jmsjmkt', 'jk01', 'Kamis'),
('jmsjmkt', 'jk01', 'Jumat'),
('jmsjmkt', 'jk01', 'Sabtu'),
('jmsjmkt', 'jk01', 'Minggu'),
('jmsjhrd', 'jk01', 'Senin'),
('jmsjhrd', 'jk01', 'Selasa'),
('jmsjhrd', 'jk01', 'Rabu'),
('jmsjhrd', 'jk01', 'Kamis'),
('jmsjhrd', 'jk01', 'Jumat'),
('jmsjhrd', 'jk01', 'Sabtu'),
('jmsjhrd', 'jk01', 'Minggu'),
('jrmhhrd', 'jk01', 'Senin'),
('jrmhhrd', 'jk01', 'Selasa'),
('jrmhhrd', 'jk01', 'Rabu'),
('jrmhhrd', 'jk01', 'Kamis'),
('jrmhhrd', 'jk01', 'Jumat'),
('jrmhhrd', 'jk01', 'Sabtu'),
('jrmhhrd', 'jk01', 'Minggu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_lokasi`
--

CREATE TABLE `konfigurasi_lokasi` (
  `id` int(11) NOT NULL,
  `lokasi_kantor` varchar(255) NOT NULL,
  `radius` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `konfigurasi_lokasi`
--

INSERT INTO `konfigurasi_lokasi` (`id`, `lokasi_kantor`, `radius`) VALUES
(1, '-6.235104272550494,106.79932456653842', 50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_cuti`
--

CREATE TABLE `pengajuan_cuti` (
  `kode_cuti` char(3) NOT NULL,
  `nama_cuti` varchar(30) DEFAULT NULL,
  `jml_hari` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_cuti`
--

INSERT INTO `pengajuan_cuti` (`kode_cuti`, `nama_cuti`, `jml_hari`) VALUES
('c01', 'Cuti Tahunan', 12),
('c02', 'Cuti Sakit', 12),
('c03', 'Cuti Diluar Tanggungan', 90),
('c04', 'Cuti Melahirkan', 90);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `kode_izin` char(9) NOT NULL,
  `kode_cuti` char(3) DEFAULT NULL,
  `nik` char(5) DEFAULT NULL,
  `tgl_izin_dari` date DEFAULT NULL,
  `tgl_izin_sampai` date DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status_approved` char(1) DEFAULT '0',
  `doc_sid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_izin`
--

INSERT INTO `pengajuan_izin` (`kode_izin`, `kode_cuti`, `nik`, `tgl_izin_dari`, `tgl_izin_sampai`, `status`, `keterangan`, `status_approved`, `doc_sid`) VALUES
('iz1223001', NULL, '1234', '2023-12-26', '2023-12-28', 'i', 'Menjenguk Adik', '1', NULL),
('iz1223002', NULL, '1234', '2023-12-30', '2023-12-31', 's', 'Sakit perut', '1', 'iz1223002.png'),
('iz1223003', 'c01', '1234', '2023-12-01', '2023-12-05', 'c', 'Pernikahan adik', '1', NULL),
('iz1223004', 'c01', '2222', '2023-12-26', '2023-12-30', 'c', 'Liburan akhir tahun', '1', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` int(15) NOT NULL,
  `nik` char(5) NOT NULL,
  `tgl_presensi` date NOT NULL,
  `jam_in` time DEFAULT NULL,
  `jam_out` time DEFAULT NULL,
  `foto_in` varchar(255) DEFAULT NULL,
  `foto_out` varchar(255) DEFAULT NULL,
  `lokasi_in` text DEFAULT NULL,
  `lokasi_out` text DEFAULT NULL,
  `kode_jam_kerja` char(4) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `kode_izin` char(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `presensi`
--

INSERT INTO `presensi` (`id`, `nik`, `tgl_presensi`, `jam_in`, `jam_out`, `foto_in`, `foto_out`, `lokasi_in`, `lokasi_out`, `kode_jam_kerja`, `status`, `kode_izin`) VALUES
(1, '1236', '2023-12-27', '20:51:32', NULL, '1236_2023-12-27_in.png', NULL, '-6.243176999999999,106.750999', NULL, 'jk05', 'h', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Donarsi Yosianto', 'donarazhar@gmail.com', NULL, '$2y$12$vX7Y4yhjQUSVdPPMvCkh4.FQphXa5sMPoHFjsPeqxlkEcu2T0aQKu', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`kode_cabang`);

--
-- Indeks untuk tabel `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`kode_dept`);

--
-- Indeks untuk tabel `jam_kerja`
--
ALTER TABLE `jam_kerja`
  ADD PRIMARY KEY (`kode_jam_kerja`);

--
-- Indeks untuk tabel `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indeks untuk tabel `konfigurasi_jk_dept`
--
ALTER TABLE `konfigurasi_jk_dept`
  ADD PRIMARY KEY (`kode_jk_dept`);

--
-- Indeks untuk tabel `konfigurasi_jk_dept_detail`
--
ALTER TABLE `konfigurasi_jk_dept_detail`
  ADD KEY `fk_jkdept` (`kode_jk_dept`);

--
-- Indeks untuk tabel `konfigurasi_lokasi`
--
ALTER TABLE `konfigurasi_lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengajuan_cuti`
--
ALTER TABLE `pengajuan_cuti`
  ADD PRIMARY KEY (`kode_cuti`);

--
-- Indeks untuk tabel `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`kode_izin`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `konfigurasi_jk_dept_detail`
--
ALTER TABLE `konfigurasi_jk_dept_detail`
  ADD CONSTRAINT `fk_jkdept` FOREIGN KEY (`kode_jk_dept`) REFERENCES `konfigurasi_jk_dept` (`kode_jk_dept`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
