-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Feb 2018 pada 20.25
-- Versi server: 10.1.25-MariaDB
-- Versi PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simkes_apotek`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pembelian`
--

CREATE TABLE `ak_data_pembelian` (
  `id_pembelian` char(20) NOT NULL COMMENT 'FK2101180001',
  `id_pesanan` char(20) DEFAULT NULL,
  `id_pbf` char(20) NOT NULL,
  `tanggal_pembelian` date DEFAULT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `qty_datang` int(11) NOT NULL DEFAULT '0',
  `subtotal` decimal(8,2) NOT NULL DEFAULT '0.00',
  `jenis` enum('Supplier','Gudang') NOT NULL DEFAULT 'Supplier'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_pembelian`
--

INSERT INTO `ak_data_pembelian` (`id_pembelian`, `id_pesanan`, `id_pbf`, `tanggal_pembelian`, `tanggal_jatuh_tempo`, `qty_datang`, `subtotal`, `jenis`) VALUES
('FK18022600001', NULL, 'PBF00001', '2018-02-26', '2019-02-28', 0, '0.00', 'Supplier');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ak_data_pembelian`
--
ALTER TABLE `ak_data_pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ak_data_pembelian`
--
ALTER TABLE `ak_data_pembelian`
  ADD CONSTRAINT `ak_data_pembelian_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `ak_data_pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
