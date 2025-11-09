-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2025 pada 16.34
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absen_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_attidance`
--

CREATE TABLE `tb_attidance` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `check_in_status` enum('On Time','Late') DEFAULT NULL,
  `check_out_status` enum('On Time','Early Leave','Overtime') DEFAULT NULL,
  `attedance_status` enum('Present','Sick','Leave','Absent') NOT NULL,
  `check_in_image` varchar(255) DEFAULT NULL,
  `check_out_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_role`
--

CREATE TABLE `tb_role` (
  `id` bigint(20) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_role`
--

INSERT INTO `tb_role` (`id`, `role_name`, `role_description`) VALUES
(1, 'Admin', 'Full administrative access and system management privileges.'),
(2, 'HR', 'Manages employee records, attendance approvals, and company policies.'),
(3, 'Employee', 'Standard user role for daily operations and attendance logging.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_shift`
--

CREATE TABLE `tb_shift` (
  `id` bigint(20) NOT NULL,
  `shift_name` varchar(255) NOT NULL,
  `check_in_time` time NOT NULL,
  `check_out_time` time NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_shift`
--

INSERT INTO `tb_shift` (`id`, `shift_name`, `check_in_time`, `check_out_time`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, 'Shift Malam', '00:02:12', '10:00:00', '2025-11-04 12:24:39', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `shift_id` bigint(20) DEFAULT NULL,
  `username` varchar(225) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id`, `role_id`, `shift_id`, `username`, `phone`, `password`, `is_active`, `last_login`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 2, 'John D', '0812222222', '$2y$10$JIz4RucQ1rDVFDPvBNE2YuZw0WXuMWD4pDsnBkqP8zmGsuZPO5w8K', 1, NULL, '2025-11-03 16:38:44', NULL, '2025-11-09 20:58:10', NULL),
(2, 2, 2, 'Jane Smith', '081122334455', '$2y$10$apy5Zvn8wDXnA44NEVIOA.aeY5TjUIZKnkeMmayQ1lSSg0d/q5k9y', 1, NULL, '2025-11-03 16:38:44', NULL, '2025-11-04 12:36:18', NULL),
(3, 3, 2, 'Budi Santoso', '085544332211', '$2y$10$hd61tVlmKif3GsghR8QcKOke6V5q02v.TJ6ghAnf7E5lGd/uJ58lS', 1, NULL, '2025-11-03 16:38:44', NULL, '2025-11-04 17:16:27', NULL),
(15, 1, 2, 'Aldi13210', '081234560900', '$2y$10$izHxgWHGC.qPUuSJrEJG4un953IClz4UcPJ5v6Jsz84I8jXlySVV.', 1, NULL, '2025-11-09 20:55:32', NULL, '2025-11-09 20:58:28', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_attidance`
--
ALTER TABLE `tb_attidance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_date` (`user_id`,`date`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indeks untuk tabel `tb_role`
--
ALTER TABLE `tb_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indeks untuk tabel `tb_shift`
--
ALTER TABLE `tb_shift`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_attidance`
--
ALTER TABLE `tb_attidance`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_role`
--
ALTER TABLE `tb_role`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_shift`
--
ALTER TABLE `tb_shift`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_attidance`
--
ALTER TABLE `tb_attidance`
  ADD CONSTRAINT `tb_attidance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_attidance_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `tb_shift` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `tb_role` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_user_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `tb_shift` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
