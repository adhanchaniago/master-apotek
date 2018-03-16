-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Feb 2018 pada 20.24
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
-- Struktur dari tabel `ak_data_pembelian_detail`
--

CREATE TABLE `ak_data_pembelian_detail` (
  `id_pembelian_detail` bigint(20) NOT NULL,
  `id_pembelian` char(20) NOT NULL,
  `id_barang` bigint(20) NOT NULL,
  `qty_raw` int(11) NOT NULL DEFAULT '0',
  `qty_fix` int(11) NOT NULL DEFAULT '0',
  `diskon` tinyint(100) DEFAULT '0',
  `subtotal_barang` decimal(8,2) NOT NULL DEFAULT '0.00',
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ak_data_pembelian_detail`
--
ALTER TABLE `ak_data_pembelian_detail`
  ADD PRIMARY KEY (`id_pembelian_detail`),
  ADD KEY `id_pesanan` (`id_pembelian`),
  ADD KEY `ak_data_pesanan_detail_ibfk_1` (`id_barang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ak_data_pembelian_detail`
--
ALTER TABLE `ak_data_pembelian_detail`
  MODIFY `id_pembelian_detail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
