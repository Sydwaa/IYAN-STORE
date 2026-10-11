-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Okt 2026 pada 15.29
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
-- Database: `toko_ff`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `rank` varchar(60) NOT NULL DEFAULT '',
  `skin` varchar(255) NOT NULL DEFAULT '',
  `harga` int(11) NOT NULL DEFAULT 0,
  `status` enum('tersedia','terjual') NOT NULL DEFAULT 'tersedia',
  `gambar` varchar(120) NOT NULL DEFAULT '',
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`id`, `nama`, `level`, `rank`, `skin`, `harga`, `status`, `gambar`, `dibuat`) VALUES
(2, 'Akun sultan', 78, '', 'Bejibun', 79165, 'tersedia', 'ff-1791342193-c39e27.jpg', '2026-10-07 03:03:13'),
(4, 'Sg Lumut', 88, '', 'Bejibun', 75, 'tersedia', 'ff-1791343233-f4c2d9.jpg', '2026-10-07 03:20:33'),
(5, 'tes', 88, '', 'Bejibun', 88888, 'terjual', 'ff-1791343838-a74590.jpg', '2026-10-07 03:30:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
