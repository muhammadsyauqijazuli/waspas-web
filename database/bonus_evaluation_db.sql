-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Jul 2025 pada 15.53
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
-- Database: `bonus_evaluation_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2025-07-14 19:58:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_waspas`
--

CREATE TABLE `hasil_waspas` (
  `montir_id` int(11) NOT NULL,
  `score_waspas` decimal(10,4) NOT NULL,
  `score_hybrid` decimal(10,4) DEFAULT NULL,
  `tanggal_perhitungan` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `bobot_waspas` decimal(5,4) NOT NULL,
  `bobot_ahp` decimal(5,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama`, `bobot_waspas`, `bobot_ahp`) VALUES
(1, 'omset_pemasukan', 0.4000, 0.3500),
(2, 'kecepatan', 0.2500, 0.2500),
(3, 'kedisiplinan', 0.2000, 0.2500),
(4, 'kepuasan', 0.1500, 0.1500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `montir`
--

CREATE TABLE `montir` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kecepatan` decimal(5,2) NOT NULL,
  `kedisiplinan` decimal(5,2) NOT NULL,
  `kepuasan` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `montir`
--

INSERT INTO `montir` (`id`, `nama`, `kecepatan`, `kedisiplinan`, `kepuasan`) VALUES
(1, 'Khalis', 80.00, 90.00, 85.00),
(2, 'Yusuf', 75.00, 85.00, 80.00),
(3, 'Joel', 88.00, 92.00, 87.00);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `omset_montir`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `omset_montir` (
`montir_id` int(11)
,`total_omset` decimal(37,2)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `montir_id` int(11) NOT NULL,
  `pemasukan` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `montir_id`, `pemasukan`, `tanggal`) VALUES
(1, 1, 200000.00, '2025-07-01'),
(2, 1, 250000.00, '2025-07-02'),
(3, 2, 300000.00, '2025-07-01'),
(4, 3, 400000.00, '2025-07-01'),
(5, 3, 350000.00, '2025-07-03'),
(6, 1, 100000.00, '2025-04-01'),
(7, 1, 135000.00, '2025-04-02'),
(8, 1, 170000.00, '2025-04-03'),
(9, 1, 515000.00, '2025-04-04'),
(10, 1, 605000.00, '2025-04-05'),
(11, 1, 365000.00, '2025-04-06'),
(12, 1, 155000.00, '2025-04-07'),
(13, 1, 370000.00, '2025-04-08'),
(14, 1, 1010000.00, '2025-04-10'),
(15, 1, 130000.00, '2025-04-11'),
(16, 1, 450000.00, '2025-04-12'),
(17, 1, 435000.00, '2025-04-13'),
(18, 1, 140000.00, '2025-04-14'),
(19, 1, 225000.00, '2025-04-15'),
(20, 1, 435000.00, '2025-04-16'),
(21, 1, 455000.00, '2025-04-17'),
(22, 1, 300000.00, '2025-04-18'),
(23, 1, 290000.00, '2025-04-19'),
(24, 1, 250000.00, '2025-04-20'),
(25, 1, 530000.00, '2025-04-21'),
(26, 1, 555000.00, '2025-04-22'),
(27, 1, 220000.00, '2025-04-23'),
(28, 1, 320000.00, '2025-04-24'),
(29, 1, 255000.00, '2025-04-25'),
(30, 1, 110000.00, '2025-04-26'),
(31, 1, 330000.00, '2025-04-27'),
(32, 1, 70000.00, '2025-04-28'),
(33, 1, 65000.00, '2025-04-30'),
(34, 1, 545000.00, '2025-05-01'),
(35, 1, 420000.00, '2025-05-02'),
(36, 2, 70000.00, '2025-04-01'),
(37, 2, 130000.00, '2025-04-02'),
(38, 2, 260000.00, '2025-04-03'),
(39, 2, 730000.00, '2025-04-04'),
(40, 2, 450000.00, '2025-04-05'),
(41, 2, 515000.00, '2025-04-06'),
(42, 2, 3365000.00, '2025-04-07'),
(43, 2, 310000.00, '2025-04-08'),
(44, 2, 1385000.00, '2025-04-10'),
(45, 2, 995000.00, '2025-04-11'),
(46, 2, 1820000.00, '2025-04-12'),
(47, 2, 760000.00, '2025-04-13'),
(48, 2, 670000.00, '2025-04-14'),
(49, 2, 1710000.00, '2025-04-15'),
(50, 2, 925000.00, '2025-04-16'),
(51, 2, 1020000.00, '2025-04-17'),
(52, 2, 320000.00, '2025-04-18'),
(53, 2, 910000.00, '2025-04-19'),
(54, 2, 360000.00, '2025-04-20'),
(55, 2, 775000.00, '2025-04-21'),
(56, 2, 785000.00, '2025-04-22'),
(57, 2, 1175000.00, '2025-04-23'),
(58, 2, 975000.00, '2025-04-24'),
(59, 2, 1180000.00, '2025-04-25'),
(60, 2, 80000.00, '2025-04-26'),
(61, 2, 690000.00, '2025-04-27'),
(62, 2, 940000.00, '2025-04-28'),
(63, 2, 1860000.00, '2025-04-30'),
(64, 2, 10000.00, '2025-05-01'),
(65, 2, 295000.00, '2025-05-02'),
(66, 2, 735000.00, '2025-05-03'),
(67, 2, 1430000.00, '2025-05-04'),
(68, 2, 235000.00, '2025-05-05'),
(69, 2, 970000.00, '2025-05-06'),
(70, 2, 705000.00, '2025-05-07'),
(71, 2, 1120000.00, '2025-05-08'),
(72, 3, 95000.00, '2025-04-01'),
(73, 3, 140000.00, '2025-04-02'),
(74, 3, 90000.00, '2025-04-03'),
(75, 3, 130000.00, '2025-04-04'),
(76, 3, 145000.00, '2025-04-05'),
(77, 3, 115000.00, '2025-04-06'),
(78, 3, 150000.00, '2025-04-07'),
(79, 3, 45000.00, '2025-04-08'),
(80, 3, 205000.00, '2025-04-10'),
(81, 3, 110000.00, '2025-04-11'),
(82, 3, 60000.00, '2025-04-12'),
(83, 3, 120000.00, '2025-04-13'),
(84, 3, 140000.00, '2025-04-14'),
(85, 3, 150000.00, '2025-04-15'),
(86, 3, 100000.00, '2025-04-16'),
(87, 3, 120000.00, '2025-04-17'),
(88, 3, 240000.00, '2025-04-18'),
(89, 3, 20000.00, '2025-04-19'),
(90, 3, 205000.00, '2025-04-20'),
(91, 3, 90000.00, '2025-04-21'),
(92, 3, 110000.00, '2025-04-22'),
(93, 3, 250000.00, '2025-04-23'),
(94, 3, 285000.00, '2025-04-24');

-- --------------------------------------------------------

--
-- Struktur untuk view `omset_montir`
--
DROP TABLE IF EXISTS `omset_montir`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `omset_montir`  AS SELECT `m`.`id` AS `montir_id`, sum(`t`.`pemasukan`) AS `total_omset` FROM (`montir` `m` left join `transaksi` `t` on(`m`.`id` = `t`.`montir_id`)) GROUP BY `m`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `hasil_waspas`
--
ALTER TABLE `hasil_waspas`
  ADD PRIMARY KEY (`montir_id`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `montir`
--
ALTER TABLE `montir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `montir_id` (`montir_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `montir`
--
ALTER TABLE `montir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil_waspas`
--
ALTER TABLE `hasil_waspas`
  ADD CONSTRAINT `hasil_waspas_ibfk_1` FOREIGN KEY (`montir_id`) REFERENCES `montir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`montir_id`) REFERENCES `montir` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
