-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 05 Feb 2026 pada 00.58
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
-- Database: `dbfutsal`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_212279`
--

CREATE TABLE `admin_212279` (
  `212279_id_user` int(3) NOT NULL,
  `212279_username` varchar(20) NOT NULL,
  `212279_password` varchar(20) NOT NULL,
  `212279_nama` varchar(50) NOT NULL,
  `212279_no_handphone` varchar(15) NOT NULL,
  `212279_email` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `admin_212279`
--

INSERT INTO `admin_212279` (`212279_id_user`, `212279_username`, `212279_password`, `212279_nama`, `212279_no_handphone`, `212279_email`) VALUES
(9, 'admin', 'admin123', 'admin', '088276477014', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bayar_212279`
--

CREATE TABLE `bayar_212279` (
  `212279_id_bayar` int(11) NOT NULL,
  `212279_id_sewa` int(11) NOT NULL,
  `212279_bukti` text NOT NULL,
  `212279_tanggal_upload` date NOT NULL,
  `212279_konfirmasi` varchar(50) NOT NULL DEFAULT 'Belum'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `bayar_212279`
--

INSERT INTO `bayar_212279` (`212279_id_bayar`, `212279_id_sewa`, `212279_bukti`, `212279_tanggal_upload`, `212279_konfirmasi`) VALUES
(58, 129, '67878d8cd5caf.jpg', '2025-01-15', 'Terkonfirmasi'),
(60, 131, '6787dcc0786ba.png', '2025-01-15', 'Terkonfirmasi'),
(61, 135, 'SEWA-135-1770079109', '2026-02-03', 'Terkonfirmasi'),
(62, 139, 'SEWA-139-1770249121', '2026-02-05', 'Terkonfirmasi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_212279`
--

CREATE TABLE `harga_212279` (
  `212279_id_harga` int(11) NOT NULL,
  `212279_hari` varchar(10) NOT NULL,
  `212279_jam_mulai` tinyint(4) NOT NULL,
  `212279_jam_selesai` tinyint(4) NOT NULL,
  `212279_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `harga_212279`
--

INSERT INTO `harga_212279` (`212279_id_harga`, `212279_hari`, `212279_jam_mulai`, `212279_jam_selesai`, `212279_harga`) VALUES
(2, 'weekday', 8, 16, 100000),
(3, 'weekday', 16, 22, 150000),
(4, 'weekend', 8, 22, 170000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lapangan_212279`
--

CREATE TABLE `lapangan_212279` (
  `212279_id_lapangan` int(11) NOT NULL,
  `212279_nama` varchar(35) NOT NULL,
  `212279_keterangan` text NOT NULL,
  `212279_harga` int(11) NOT NULL,
  `212279_foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `lapangan_212279`
--

INSERT INTO `lapangan_212279` (`212279_id_lapangan`, `212279_nama`, `212279_keterangan`, `212279_harga`, `212279_foto`) VALUES
(23, 'Bronze', 'Paket ini dirancang untuk pelanggan yang menginginkan pengalaman bermain futsal dengan harga terjangkau.', 10000, 'footbal.jpg'),
(24, 'Silver', 'Pilihan ideal untuk pelanggan yang ingin sedikit lebih dari sekadar bermain futsal.', 30000, 'badmintoon.jpg'),
(25, 'Gold', 'Paket premium untuk pemain futsal yang mengutamakan kenyamanan dan fasilitas tambahan.', 30000, 'basket.jpg'),
(26, 'Diamond', 'Paket eksklusif untuk pengalaman bermain futsal terbaik. ', 40000, 'futsal.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `membership_212279`
--

CREATE TABLE `membership_212279` (
  `212279_id_membership` int(11) NOT NULL,
  `212279_nama` varchar(50) NOT NULL,
  `212279_harga` int(11) NOT NULL,
  `212279_durasi_jam` int(11) NOT NULL,
  `212279_kapasitas` int(11) NOT NULL,
  `212279_bola_gratis` tinyint(1) NOT NULL DEFAULT 1,
  `212279_minuman_gratis` tinyint(1) NOT NULL DEFAULT 0,
  `212279_diskon_hari_kerja` tinyint(1) NOT NULL DEFAULT 0,
  `212279_featured` tinyint(1) NOT NULL DEFAULT 0,
  `212279_populer` tinyint(1) NOT NULL DEFAULT 0,
  `212279_urutan` int(11) NOT NULL DEFAULT 0,
  `212279_aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `membership_212279`
--

INSERT INTO `membership_212279` (`212279_id_membership`, `212279_nama`, `212279_harga`, `212279_durasi_jam`, `212279_kapasitas`, `212279_bola_gratis`, `212279_minuman_gratis`, `212279_diskon_hari_kerja`, `212279_featured`, `212279_populer`, `212279_urutan`, `212279_aktif`) VALUES
(1, 'Bronze', 10000, 1, 10, 1, 0, 0, 0, 0, 1, 1),
(2, 'Silver', 30000, 2, 12, 1, 1, 0, 1, 0, 2, 1),
(3, 'Gold', 30000, 3, 15, 1, 1, 1, 0, 0, 3, 1),
(4, 'Diamond', 40000, 4, 20, 1, 1, 1, 0, 1, 4, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sewa_212279`
--

CREATE TABLE `sewa_212279` (
  `212279_id_sewa` int(11) NOT NULL,
  `212279_id_user` int(11) NOT NULL,
  `212279_id_lapangan` int(11) NOT NULL,
  `212279_tanggal_pesan` date NOT NULL,
  `212279_lama_sewa` int(11) NOT NULL,
  `212279_jam_mulai` datetime NOT NULL,
  `212279_jam_habis` datetime NOT NULL,
  `212279_harga` int(11) NOT NULL,
  `212279_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `sewa_212279`
--

INSERT INTO `sewa_212279` (`212279_id_sewa`, `212279_id_user`, `212279_id_lapangan`, `212279_tanggal_pesan`, `212279_lama_sewa`, `212279_jam_mulai`, `212279_jam_habis`, `212279_harga`, `212279_total`) VALUES
(129, 100, 25, '2025-01-15', 12, '2025-01-15 10:28:00', '2025-01-15 22:28:00', 30000, 360000),
(131, 100, 23, '2025-01-15', 1, '2025-01-15 23:04:00', '2025-01-16 00:04:00', 10000, 10000),
(135, 100, 23, '2026-02-03', 11, '2026-02-13 06:18:00', '2026-02-13 17:18:00', 10000, 110000),
(138, 100, 23, '2026-02-04', 15, '2026-02-04 23:00:00', '2026-02-05 14:00:00', 10000, 250000),
(139, 101, 23, '2026-02-05', 10, '2026-02-12 06:51:00', '2026-02-12 16:51:00', 10000, 820000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_212279`
--

CREATE TABLE `user_212279` (
  `212279_id_user` int(11) NOT NULL,
  `212279_email` varchar(50) NOT NULL,
  `212279_password` varchar(32) NOT NULL,
  `212279_no_handphone` varchar(20) NOT NULL,
  `212279_jenis_kelamin` varchar(10) NOT NULL,
  `212279_nama_lengkap` varchar(60) NOT NULL,
  `212279_alamat` text NOT NULL,
  `212279_foto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `user_212279`
--

INSERT INTO `user_212279` (`212279_id_user`, `212279_email`, `212279_password`, `212279_no_handphone`, `212279_jenis_kelamin`, `212279_nama_lengkap`, `212279_alamat`, `212279_foto`) VALUES
(101, 'rizki.qq05@gmail.com', 'kiki05', '088276477014', 'Laki-Laki', 'Muhammad Rzki Pratama', 'JL Sakura no 2 kec tanjung senang kel way kandis', '6983db62bf784.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_212279`
--
ALTER TABLE `admin_212279`
  ADD PRIMARY KEY (`212279_id_user`);

--
-- Indeks untuk tabel `bayar_212279`
--
ALTER TABLE `bayar_212279`
  ADD PRIMARY KEY (`212279_id_bayar`);

--
-- Indeks untuk tabel `harga_212279`
--
ALTER TABLE `harga_212279`
  ADD PRIMARY KEY (`212279_id_harga`);

--
-- Indeks untuk tabel `lapangan_212279`
--
ALTER TABLE `lapangan_212279`
  ADD PRIMARY KEY (`212279_id_lapangan`);

--
-- Indeks untuk tabel `membership_212279`
--
ALTER TABLE `membership_212279`
  ADD PRIMARY KEY (`212279_id_membership`);

--
-- Indeks untuk tabel `sewa_212279`
--
ALTER TABLE `sewa_212279`
  ADD PRIMARY KEY (`212279_id_sewa`);

--
-- Indeks untuk tabel `user_212279`
--
ALTER TABLE `user_212279`
  ADD PRIMARY KEY (`212279_id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_212279`
--
ALTER TABLE `admin_212279`
  MODIFY `212279_id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `bayar_212279`
--
ALTER TABLE `bayar_212279`
  MODIFY `212279_id_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `harga_212279`
--
ALTER TABLE `harga_212279`
  MODIFY `212279_id_harga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `lapangan_212279`
--
ALTER TABLE `lapangan_212279`
  MODIFY `212279_id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `membership_212279`
--
ALTER TABLE `membership_212279`
  MODIFY `212279_id_membership` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `sewa_212279`
--
ALTER TABLE `sewa_212279`
  MODIFY `212279_id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT untuk tabel `user_212279`
--
ALTER TABLE `user_212279`
  MODIFY `212279_id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
