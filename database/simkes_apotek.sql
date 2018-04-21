-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 15 Apr 2018 pada 12.10
-- Versi Server: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simkes_apotek`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_create_pembelian` (IN `in_id_komputer` TINYINT, IN `in_id_pbf` CHAR(20), IN `in_id_user` CHAR(20), IN `in_tanggal_pembelian` DATE, IN `in_tanggal_jatuh_tempo` DATE, IN `in_qty_datang` INT, IN `in_konsinyasi` TINYINT)  BEGIN
INSERT INTO ak_sequence_data_pembelian VALUES (NULL);
SET @seq_id = LAST_INSERT_ID();
SET @id_pembelian = CONCAT('FK', DATE_FORMAT(NOW(), "%d%m%y"), LPAD(@seq_id, 5, '0'));
SET @subtotal = (SELECT SUM(subtotal_barang) FROM ak_data_pembelian_detail WHERE id_komputer=`in_id_komputer`);
INSERT INTO ak_data_pembelian (
	id_pembelian,
	id_pesanan,
	id_pbf,
	id_user,
	tanggal_pembelian,
	tanggal_jatuh_tempo,
	qty_datang,
	konsinyasi
) 
	VALUES (
		@id_pembelian,
		NULL,
		in_id_pbf,
		in_id_user,
		in_tanggal_pembelian,
		in_tanggal_jatuh_tempo,
		in_qty_datang,
		in_konsinyasi
	);
UPDATE ak_data_pembelian_detail 
	SET id_pembelian=@id_pembelian
	WHERE id_pembelian IS NULL;
UPDATE ak_data_barang_stok_masuk 
	SET id_pembelian=@id_pembelian, `fixed`=1
	WHERE id_pembelian IS NULL AND id_komputer=in_id_komputer;
SET @id_barang = (SELECT id_barang FROM ak_data_barang_stok_masuk WHERE id_pembelian = @id_pembelian);
SET @stok_masuk = (SELECT SUM(stok_masuk) FROM ak_data_barang_stok_masuk WHERE id_pembelian = @id_pembelian);
SET @stok_tersedia = (SELECT stok FROM ak_data_barang WHERE id_barang=@id_barang);
SET @stok = @stok_tersedia+@stok_masuk;
UPDATE ak_data_barang
	SET stok = @stok
	WHERE id_barang = @id_barang;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_delete_pj_barang_detail` (IN `in_id_penjualan_bebas_detail` BIGINT)  BEGIN
	DELETE FROM ak_data_barang_stok_keluar WHERE id_penjualan_bebas_detail=in_id_penjualan_bebas_detail;
	DELETE FROM ak_data_penjualan_bebas_detail WHERE id_penjualan_bebas_detail=in_id_penjualan_bebas_detail;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_insert_brg_pembelian` (IN `in_id_barang` INT, IN `in_qty_fix` INT, IN `in_diskon` INT, IN `in_subtotal_barang` DECIMAL(10,0), IN `in_batch` CHAR(20), IN `in_kadaluarsa` DATE, IN `in_id_komputer` INT)  BEGIN
	SET @id_pembelian = NULL;
		INSERT INTO ak_data_pembelian_detail (
		id_pembelian, 
		id_barang,
		qty_fix,
		diskon,
		subtotal_barang
	) VALUES (
		@id_pembelian,
		in_id_barang,
		in_qty_fix,
		in_diskon,
		in_subtotal_barang
	);
	INSERT INTO ak_data_barang_stok_masuk (
		id_barang,
		id_pembelian,
		stok_masuk,
		id_komputer
	) VALUES (
		in_id_barang,
		NULL,
		in_qty_fix,
		in_id_komputer
	);
	INSERT INTO ak_data_barang_detail (
		id_barang,
		batch,
		kadaluarsa,
		stok_tersedia
	) VALUES (
		in_id_barang,
		in_batch,
		in_kadaluarsa,
		in_qty_fix
	);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_insert_penjualan_bebas` (IN `in_id_user` CHAR(20), IN `in_nama_pasien` CHAR(50), IN `in_bayar` DECIMAL(10,2), IN `in_id_komputer` INT)  BEGIN
INSERT INTO ak_sequence_data_penjualan_bebas VALUES (NULL);
SET @id_jenis_penjualan = CONCAT('B', LPAD(LAST_INSERT_ID(), 5, '0'));
SET @subtotal = (SELECT SUM(total_penjualan_bebas) FROM ak_data_penjualan_bebas_detail WHERE id_users=`in_id_user`);
SET @pembulatan = (SELECT SUM(pembulatan_penjualan_bebas) FROM ak_data_penjualan_bebas_detail WHERE id_users=`in_id_user`);
SET @kembalian = in_bayar-@subtotal;
INSERT INTO ak_sequence_data_penjualan VALUES (NULL);
SET @seq_id = LAST_INSERT_ID();
SET @id_penjualan = CONCAT('TR', DATE_FORMAT(NOW(), "%d%m%y"), LPAD(@seq_id, 5, '0'));
INSERT INTO ak_data_penjualan (
	id_penjualan,
	id_user,
	pembulatan,
	bayar,
	kembali,
	subtotal,
	`status`
) 
	VALUES (
		@id_penjualan,
		`in_id_user`,
		@pembulatan,
		in_bayar,
		@kembalian,
		@subtotal,
		1
	);
INSERT INTO ak_data_penjualan_bebas (
	id_penjualan,
	id_penjualan_bebas,
	nm_pasien
)
	VALUES (
		@id_penjualan,
		@id_jenis_penjualan,
		in_nama_pasien
	);
UPDATE ak_data_penjualan_bebas_detail 
	SET id_penjualan_bebas=@id_jenis_penjualan
	WHERE id_penjualan_bebas IS NULL;
UPDATE ak_data_barang_stok_keluar 
	SET id_penjualan=@id_penjualan, `fixed`=1
	WHERE id_penjualan IS NULL AND id_komputer=in_id_komputer;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_insert_penjualan_bebas_detail` (IN `in_id_barang_detail` BIGINT, IN `in_id_users` CHAR(20), IN `in_qty_penjualan_bebas` INT, IN `in_diskon_penjualan_bebas` DECIMAL(10,0), IN `in_pembulatan_penjualan_bebas` DECIMAL(10,0), IN `in_total_penjualan_bebas` DECIMAL(10,0), IN `in_id_komputer` INT)  BEGIN
	SET @id_penjualan_bebas = NULL;
	SET @id_barang = (SELECT id_barang FROM ak_data_barang_detail WHERE id_detail_barang=in_id_barang_detail);
	INSERT INTO ak_data_penjualan_bebas_detail (
		id_penjualan_bebas, 
		id_barang_detail,
		id_users,
		id_komputer,
		qty_penjualan_bebas,
		diskon_penjualan_bebas,
		pembulatan_penjualan_bebas,
		total_penjualan_bebas
	) VALUES (
		@id_penjualan_bebas,
		in_id_barang_detail,
		in_id_users,
		in_id_komputer,
		in_qty_penjualan_bebas,
		in_diskon_penjualan_bebas,
		in_pembulatan_penjualan_bebas,
		in_total_penjualan_bebas
	);
	SET @id_pj_detail = LAST_INSERT_ID();
	INSERT INTO ak_data_barang_stok_keluar (
		id_barang,
		id_penjualan,
		id_penjualan_bebas_detail,
		id_komputer,
		stok_keluar
	) VALUES (
		@id_barang,
		NULL,
		@id_pj_detail,
		in_id_komputer,
		in_qty_penjualan_bebas
	);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_insert_penjualan_resep` ()  BEGIN

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_insert_penjualan_resep_detail` (IN `in_id_barang_detail` BIGINT, IN `in_id_users` CHAR(20), IN `in_etiket` TEXT, IN `in_qty_penjualan_resep` INT, IN `in_diskon_penjualan_resep` DECIMAL(10,0), IN `in_pembulatan_penjualan_resep` DECIMAL(10,0), IN `in_total_penjualan_resep` INT)  BEGIN
	SET @id_penjualan_resep = NULL;
	SET @id_barang = (SELECT id_barang FROM ak_data_barang_detail WHERE id_detail_barang=in_id_barang_detail);
	INSERT INTO ak_data_penjualan_bebas_detail (
		id_penjualan_resep, 
		id_barang_detail,
		id_users,
		qty_penjualan_resep,
		diskon_penjualan_resep,
		pembulatan_penjualan_resep,
		total_penjualan_resep
	) VALUES (
		@id_penjualan_resep,
		in_id_barang_detail,
		in_id_users,
		in_qty_penjualan_resep,
		in_diskon_penjualan_resep,
		in_pembulatan_penjualan_resep,
		in_total_penjualan_resep
	);
	INSERT INTO ak_data_barang_stok_keluar (
		id_barang,
		id_penjualan,
		id_komputer,
		stok_keluar
	) VALUES (
		@id_barang,
		NULL,
		in_id_komputer,
		in_qty_penjualan_bebas
	);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_pj_terbaru` ()  BEGIN
	SET @id_penjualan = (SELECT id_penjualan FROM `ak_data_penjualan` ORDER BY id_penjualan DESC LIMIT 1);
	SELECT 
		a.*,
		b.nm_pasien,
		c.qty_penjualan_bebas,
		c.diskon_penjualan_bebas,
		c.pembulatan_penjualan_bebas,
		c.total_penjualan_bebas
	FROM `ak_data_penjualan` a
	JOIN `ak_data_penjualan_bebas` b
		ON (a.id_penjualan=b.id_penjualan)
	JOIN `ak_data_penjualan_bebas_detail` c
		ON (b.id_penjualan_bebas=c.id_penjualan_bebas)
	WHERE a.id_penjualan=@id_penjualan;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ak_procedure_show_brg_pj_bebas` (IN `in_id_komputer` INT)  BEGIN
	SELECT 
		a.id_penjualan_bebas_detail,
		c.nm_barang,
		c.harga_dasar,
		a.qty_penjualan_bebas,
		a.diskon_penjualan_bebas,
		a.pembulatan_penjualan_bebas,
		a.total_penjualan_bebas,
		d.nm_satuan
	FROM `ak_data_penjualan_bebas_detail` a
	JOIN `ak_data_barang_detail` b
		ON (a.id_barang_detail=b.id_detail_barang)
	JOIN `ak_data_barang` c
		ON (b.id_barang=c.id_barang)
	JOIN `ak_data_satuan` d
		ON (c.id_satuan=d.id_satuan)
	WHERE a.id_penjualan_bebas IS NULL AND a.id_komputer=in_id_komputer
	ORDER BY a.id_penjualan_bebas_detail DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_barang`
--

CREATE TABLE `ak_data_barang` (
  `id_barang` char(20) NOT NULL COMMENT 'BRG0000001',
  `id_jenis` char(20) NOT NULL,
  `nm_barang` char(45) NOT NULL,
  `id_pabrik` char(50) NOT NULL,
  `id_kemasan` char(20) NOT NULL,
  `id_satuan` char(20) NOT NULL,
  `isi_satuan` int(11) NOT NULL,
  `golongan_obat` enum('Generik','Non Generik') NOT NULL,
  `harga_dasar` decimal(8,2) NOT NULL DEFAULT '0.00',
  `margin` decimal(8,2) NOT NULL DEFAULT '0.00',
  `dosis` text,
  `komposisi` text,
  `indikasi` text,
  `efek_samping` text,
  `konsinyasi` tinyint(1) NOT NULL DEFAULT '0',
  `formularium` tinyint(1) NOT NULL DEFAULT '0',
  `stok_maksimum` int(11) NOT NULL DEFAULT '0',
  `stok_minimum` int(11) NOT NULL DEFAULT '0',
  `stok` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_barang`
--

INSERT INTO `ak_data_barang` (`id_barang`, `id_jenis`, `nm_barang`, `id_pabrik`, `id_kemasan`, `id_satuan`, `isi_satuan`, `golongan_obat`, `harga_dasar`, `margin`, `dosis`, `komposisi`, `indikasi`, `efek_samping`, `konsinyasi`, `formularium`, `stok_maksimum`, `stok_minimum`, `stok`, `deleted`) VALUES
('10', 'JEN00000', 'Albothyl', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Non Generik', '24200.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 10, 0),
('100', 'JEN00000', 'Betamethasone 0,1%', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1951.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('1000', 'JEN00000', 'Putri Manjakani', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5100.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1001', 'JEN00000', 'Regavit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2125.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1002', 'JEN00000', 'Madu Enak Jeruk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '667.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1003', 'JEN00000', 'Madu Enak Anggur', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '667.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1004', 'JEN00000', 'Madu Enak Stawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '667.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1005', 'JEN00000', 'Herbangin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1680.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1006', 'JEN00000', 'Madu Ratu Lebah', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '53000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1007', 'JEN00000', 'Slim Fit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '73920.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1008', 'JEN00000', 'Madu Super 80 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1009', 'JEN00000', 'Madu Super 160 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('101', 'JEN00000', 'Bejo Masuk Angin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1536.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('1010', 'JEN00000', 'M. Jahanam', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1011', 'JEN00000', 'M. Gemuk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '45000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1012', 'JEN00000', 'M. Lambung', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1013', 'JEN00000', 'M. Bima 99', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '55000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1014', 'JEN00000', 'Avail Biru', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1015', 'JEN00000', 'Avail Pink', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 0),
('1016', 'JEN00000', 'Kopi Melayu Gingseg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '100.00', '0.00', NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 0),
('1017', 'JEN00000', 'Takahi Hangat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '40000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1018', 'JEN00000', 'Chili Plast 1/2x1', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17600.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('1019', 'JEN00000', 'Inolife Asarbah', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '44200.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('102', 'JEN00000', 'Bufacort-N', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5344.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('1020', 'JEN00000', 'tes', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10000.00', '0.00', NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0, 1),
('1021', 'JEN00000', 'Asam Mefenamat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '0.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('1022', 'JEN00000', 'Acetylsistein', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '0.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('1023', 'JEN00000', 'Cendo Lyters MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Non Generik', '0.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('1024', 'JEN00000', 'Genoint Salep Mata', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Non Generik', '6800.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('103', 'JEN00000', 'Bedak Salicyl (Nelco)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4818.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('104', 'JEN00000', 'Bedak My Baby Pink', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8454.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('105', 'JEN00000', 'Bedak My Baby Kuning', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8370.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('106', 'JEN00000', 'Betadin Kumur 190 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17710.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('107', 'JEN00000', 'Biolysin Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '368.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('108', 'JEN00000', 'Balsem Geliga 40 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14537.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('109', 'JEN00000', 'Balsem Balpirik Hijau', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5682.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('11', 'JEN00000', 'Albothyl Sol 10 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Non Generik', '37345.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('110', 'JEN00000', 'Balsem Balpirik Kuning', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5682.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('111', 'JEN00000', 'Balsem Balpirik Merah', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6313.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('112', 'JEN00000', 'Bedak Marck (Rose)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11732.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('113', 'JEN00000', 'Benadryl', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19211.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('114', 'JEN00000', 'Bisolvon Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7264.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('115', 'JEN00000', 'Bufacomb Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('116', 'JEN00000', 'Batugin Elixir 300 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '36100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('117', 'JEN00000', 'Bufacaryl', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '165.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('118', 'JEN00000', 'Broadamox Forte Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('119', 'JEN00000', 'Benoson Cr 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15450.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('12', 'JEN00000', 'Alkohol 70%', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3666.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('120', 'JEN00000', 'Benoson-N Cr 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19056.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('121', 'JEN00000', 'Bodrek Extra', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1697.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('122', 'JEN00000', 'Bedak Salicyl (Nelco) Non Ment', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4818.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('123', 'JEN00000', 'Balsem Lang B.02 10 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4340.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('124', 'JEN00000', 'Bejo Masuk Angin Plus', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('125', 'JEN00000', 'Bronkris', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4598.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('126', 'JEN00000', 'Bodrex Flu & Batuk Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1620.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('127', 'JEN00000', 'Bedak Salicyl 2% (Cito)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3549.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('128', 'JEN00000', 'Bedak Marck (Cream)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12081.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('129', 'JEN00000', 'Buscopan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2913.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('13', 'JEN00000', 'AllopurinoL 100 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '119.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('130', 'JEN00000', 'Caladin Lot 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12430.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('131', 'JEN00000', 'Caladin Lot 95 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17055.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('132', 'JEN00000', 'Caladin Powder 60 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8580.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('133', 'JEN00000', 'Caladin Powder 100 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12650.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('134', 'JEN00000', 'Calortusin Sirup 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3052.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('135', 'JEN00000', 'Canesten Cr 3 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16305.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('136', 'JEN00000', 'Canesten Cr 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19811.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('137', 'JEN00000', 'Canesten Cr 10 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34794.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('138', 'JEN00000', 'Captopril 12,5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '117.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('139', 'JEN00000', 'Captopril 25 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '80.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('14', 'JEN00000', 'Allopurinol 300 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '288.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('140', 'JEN00000', 'Captopril 50 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '198.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('141', 'JEN00000', 'Cataflam 50 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5773.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('142', 'JEN00000', 'CDR Fortos', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '36080.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('143', 'JEN00000', 'CDR Sweet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34038.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('144', 'JEN00000', 'CDR Fruit Punch', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34372.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('145', 'JEN00000', 'Caviplex Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3826.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('146', 'JEN00000', 'Cefadroxil 500 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '848.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('147', 'JEN00000', 'Cefadroxil Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6700.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('148', 'JEN00000', 'Cefixime Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9973.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('149', 'JEN00000', 'Cefixime Tab 100 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1430.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('15', 'JEN00000', 'Alofar 100 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '158.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('150', 'JEN00000', 'Cerebrovit X-Cel', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14014.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('151', 'JEN00000', 'Chloramfecort-H', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10364.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('152', 'JEN00000', 'Ciprofloxacin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '305.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('153', 'JEN00000', 'Clindamycin 100 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '861.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('154', 'JEN00000', 'Coldrexin Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('155', 'JEN00000', 'Colfin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5575.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('156', 'JEN00000', 'Combantrin 125 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11666.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('157', 'JEN00000', 'Combantrin 250 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11666.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('158', 'JEN00000', 'Combantrin Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('159', 'JEN00000', 'Cotrimoxazole Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '170.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('16', 'JEN00000', 'Alofar 300 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '340.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('160', 'JEN00000', 'CTM 4 mg Cito', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '98.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('161', 'JEN00000', 'Counterpain 15 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22220.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('162', 'JEN00000', 'Counterpain 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8257.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('163', 'JEN00000', 'Cucuma Plus Sharpy Ori', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11990.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('164', 'JEN00000', 'Cucruma Sharpy Stw', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11391.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('165', 'JEN00000', 'Curcuma Sharpy Black', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11083.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('166', 'JEN00000', 'Cucuma Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9110.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('167', 'JEN00000', 'Curcuma Plus 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('168', 'JEN00000', 'Curvit Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17820.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('169', 'JEN00000', 'Curvit Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21780.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('17', 'JEN00000', 'Alpara', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Non Generik', '651.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('170', 'JEN00000', 'Cooton Bud DWS', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2565.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('171', 'JEN00000', 'Cooton Bud Bayi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2150.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('172', 'JEN00000', 'Cooton Bud Pot (Besar)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7444.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('173', 'JEN00000', 'Cooton Bud Pot Bayi (Kecil)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7333.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('174', 'JEN00000', 'Cooton Ball', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6350.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('175', 'JEN00000', 'Callusol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('176', 'JEN00000', 'Cetirizine', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '213.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('177', 'JEN00000', 'Calortusin Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '290.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('178', 'JEN00000', 'Cendo Catarlent 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23021.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('179', 'JEN00000', 'Cendo Cenfresh 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '35486.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('18', 'JEN00000', 'Ambeven', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13147.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('180', 'JEN00000', 'Cendo genta 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28872.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('181', 'JEN00000', 'Cendo Mycos 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20224.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('182', 'JEN00000', 'Cendo Xitrol MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '24802.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('183', 'JEN00000', 'Cerebrofort Gold Orange', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15015.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('184', 'JEN00000', 'Cerebrofort Gold Stw', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15015.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('185', 'JEN00000', 'Curcuma-Z', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5833.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('186', 'JEN00000', 'Cal-95', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4695.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('187', 'JEN00000', 'Cefixime 100 ml (Dexa)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '908.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('188', 'JEN00000', 'Cazetin Drops', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('189', 'JEN00000', 'Cendo Cenfresh MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23639.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('19', 'JEN00000', 'Ambroxol Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3553.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('190', 'JEN00000', 'Counterpain 30 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '35200.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('191', 'JEN00000', 'Cyclofem Inj', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8644.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('192', 'JEN00000', 'Cham S.Night 35 Rcg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '877.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('193', 'JEN00000', 'Cham S.Night 29 Rcg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1154.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('194', 'JEN00000', 'Cham S.Night 35', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7865.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('195', 'JEN00000', 'Cham Non Wings 8', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3116.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('196', 'JEN00000', 'Cham Maxi Wings 5', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2679.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('197', 'JEN00000', 'Cham Non Wings Rcg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '384.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('198', 'JEN00000', 'Cham Pentilener', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4646.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('199', 'JEN00000', 'Cham S.Night 29', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3846.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('20', 'JEN00000', 'Ambroxol Sirup (Erella)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3328.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 20, 0),
('200', 'JEN00000', 'Clonaderm', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('201', 'JEN00000', 'Camidryl Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2610.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('202', 'JEN00000', 'Camivita Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3438.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('203', 'JEN00000', 'Casetamol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3215.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('204', 'JEN00000', 'Capiplex Drops', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3558.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('205', 'JEN00000', 'Cataflam 25 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3025.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('206', 'JEN00000', 'Cendo Augentonic 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25846.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('207', 'JEN00000', 'Cendo Augentonic MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22226.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('208', 'JEN00000', 'Curcuma Grow 200 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20990.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('209', 'JEN00000', 'Cendo Floxa MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27219.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('21', 'JEN00000', 'Ambroxol Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '170.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('210', 'JEN00000', 'Cendo Xitrol 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27728.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('211', 'JEN00000', 'Celana Khitan M', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19635.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('212', 'JEN00000', 'Celana Khitan L', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19635.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('213', 'JEN00000', 'Cendo Catarlent MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20350.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('214', 'JEN00000', 'Cek Kolesterol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22445.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('215', 'JEN00000', 'Cek Asam Urat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4537.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('216', 'JEN00000', 'Cek Gula Darah', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4501.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('217', 'JEN00000', 'Cotrimoxazole Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3129.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('218', 'JEN00000', 'Cendo Timol MD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '26201.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('219', 'JEN00000', 'Counterpain Cool 15 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23320.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('22', 'JEN00000', 'Aminophyllin 200 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '106.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('220', 'JEN00000', 'Dactarin Cream 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20763.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('221', 'JEN00000', 'Damaben', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('222', 'JEN00000', 'Dapyrin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3629.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('223', 'JEN00000', 'Decadryl Sirup 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10120.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('224', 'JEN00000', 'Decolgen', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1616.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('225', 'JEN00000', 'Dettol Liquid 50 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5225.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('226', 'JEN00000', 'Dettol Liquid 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11509.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('227', 'JEN00000', 'Dexa Harsen 0,75 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '187.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('228', 'JEN00000', 'Dexa Harsen 0,5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '168.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('229', 'JEN00000', 'Dexteem Plus', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '230.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('23', 'JEN00000', 'Amlodipine 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '816.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('230', 'JEN00000', 'Dextamine', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1680.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('231', 'JEN00000', 'Dextral Forte', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '758.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('232', 'JEN00000', 'Dialet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3113.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('233', 'JEN00000', 'Diapet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1636.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('234', 'JEN00000', 'Diapet Anak Sachet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1314.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('235', 'JEN00000', 'Dulcolac 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1195.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('236', 'JEN00000', 'Dulcolac Suppo Adult', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21285.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('237', 'JEN00000', 'Dulcolac Suppo Infant', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15257.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('238', 'JEN00000', 'Dulcolactol Sirup 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '55250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('239', 'JEN00000', 'Dumin Sirup 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17311.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('24', 'JEN00000', 'Amlodipine 5 mg (Nufarindo)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '135.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('240', 'JEN00000', 'Dumin Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3889.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('241', 'JEN00000', 'Dionicol Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4888.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('242', 'JEN00000', 'Degirol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3106.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('243', 'JEN00000', 'Diabetasol Sugar \'25', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17150.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('244', 'JEN00000', 'Dr.Kang Maternity (Pembalut)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2011.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('245', 'JEN00000', 'Dr.Kang Underpad (Perelak)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4124.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('246', 'JEN00000', 'Daonil Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3404.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('247', 'JEN00000', 'Dexigen Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6999.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('248', 'JEN00000', 'Domperidon 10 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '404.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('249', 'JEN00000', 'Dermafix-T (Kecil)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8751.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('25', 'JEN00000', 'Amlodipine 10 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '187.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('250', 'JEN00000', 'Dettol Liquid 250 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28296.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('251', 'JEN00000', 'Dehaf', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8333.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('252', 'JEN00000', 'Danasone Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '99.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('253', 'JEN00000', 'Doxycycline', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '364.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('254', 'JEN00000', 'Dexanta Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '166.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('255', 'JEN00000', 'Dextral Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '438.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('256', 'JEN00000', 'Daneuron', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '373.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('257', 'JEN00000', 'Diabetasol Susu (Coklat)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38325.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('258', 'JEN00000', 'Diabetasol Susu (Vanilla)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38325.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('259', 'JEN00000', 'Dexamethasone 0,75 (Dexa)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '136.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('26', 'JEN00000', 'Amlodipine 10 mg (Hexp)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '935.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('260', 'JEN00000', 'Dentasol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('261', 'JEN00000', 'Diabetasol Susu (Cappucino)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38326.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('262', 'JEN00000', 'Dermafix-T (Besar)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15296.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('263', 'JEN00000', 'Daktarin 10 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32879.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('264', 'JEN00000', 'Diclofenak Potasium 25 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '391.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('265', 'JEN00000', 'Decubal Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23360.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('266', 'JEN00000', 'Erlamycetin Tetes Mata', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9918.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('267', 'JEN00000', 'Erlamycetin Tetes Telinga', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5189.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('268', 'JEN00000', 'Erlamycetin Salep Mata', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6125.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('269', 'JEN00000', 'Esperson Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '50455.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('27', 'JEN00000', 'Amoxicillin Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '280.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('270', 'JEN00000', 'Etabion', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '205.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('271', 'JEN00000', 'Etaflusin Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4223.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('272', 'JEN00000', 'Ever-E \'12', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('273', 'JEN00000', 'Enkasari Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17415.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('274', 'JEN00000', 'Entrasol Active (Mochacino)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25375.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('275', 'JEN00000', 'Entrasol Gold (Coklat)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30030.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('276', 'JEN00000', 'Enervon-C', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3981.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('277', 'JEN00000', 'Enbatik (Powder)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2611.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('278', 'JEN00000', 'Enzyplek', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3650.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('279', 'JEN00000', 'Enervon-C (Botol)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29161.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('28', 'JEN00000', 'AnaKonidin 30 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5584.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('280', 'JEN00000', 'Erlagin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '393.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('281', 'JEN00000', 'Etamoxul Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '186.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('282', 'JEN00000', 'Entrostop Cair Anak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1598.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('283', 'JEN00000', 'Ester-C (Strip)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4938.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('284', 'JEN00000', 'Ecodine', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5167.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('285', 'JEN00000', 'Etamox Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '327.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('286', 'JEN00000', 'Entrasol Active (Coklat)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25121.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('287', 'JEN00000', 'Enervon-C Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16690.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('288', 'JEN00000', 'Empeng Pooh', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('289', 'JEN00000', 'Egoji Anggur', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28215.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('29', 'JEN00000', 'AnaKonidin OBH 30 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6288.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('290', 'JEN00000', 'Eyevit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28598.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('291', 'JEN00000', 'Fiesta Durian', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7115.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('292', 'JEN00000', 'Fiesta 3\' Mint', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7515.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('293', 'JEN00000', 'Fiesta 3\' Banana', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7515.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('294', 'JEN00000', 'Fiesta 3\' Strawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7515.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('295', 'JEN00000', 'Fiesta 3\' Neon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11616.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('296', 'JEN00000', 'Fitkom Jeruk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13663.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('297', 'JEN00000', 'Fitkom Anggur', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13888.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('298', 'JEN00000', 'Fitkom Strawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13063.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('299', 'JEN00000', 'Fitkom Gummy Ungu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14395.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('30', 'JEN00000', 'Anastan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '246.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('300', 'JEN00000', 'Fitkom Gummy Hijau', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18362.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('301', 'JEN00000', 'Fitkom Gummy Orange', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14395.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('302', 'JEN00000', 'Fitkom Gummy Biru', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14944.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('303', 'JEN00000', 'Fenofibrate', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('304', 'JEN00000', 'Fludane Caps (Orange)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2849.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('305', 'JEN00000', 'Fludane F (Hijau)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3489.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('306', 'JEN00000', 'Fludane Plus (Merah)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3501.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('307', 'JEN00000', 'Flumin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2444.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('308', 'JEN00000', 'Foley Cath \'16', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10384.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('309', 'JEN00000', 'Foley Cath \'18', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10384.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('31', 'JEN00000', 'Anaton Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('310', 'JEN00000', 'Freshcare Original', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10372.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('311', 'JEN00000', 'Freshcare Strong', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10372.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('312', 'JEN00000', 'Fungiderm Cr 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11202.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('313', 'JEN00000', 'Fungiderm Cr 10 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('314', 'JEN00000', 'Furosemid 4 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '107.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('315', 'JEN00000', 'Fatigon Spirit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5544.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('316', 'JEN00000', 'Fasidol F tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '179.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('317', 'JEN00000', 'Fasidol Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3281.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('318', 'JEN00000', 'Flumin Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5222.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('319', 'JEN00000', 'FG Troches', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1076.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('32', 'JEN00000', 'Anaton Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('320', 'JEN00000', 'Farsifen Plus Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '214.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('321', 'JEN00000', 'Feminax', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1983.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('322', 'JEN00000', 'Fatigon Putih', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2963.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('323', 'JEN00000', 'Fargoxin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '180.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('324', 'JEN00000', 'Folamil Genio', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3811.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('325', 'JEN00000', 'Fasorbid 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '134.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('326', 'JEN00000', 'Freshcare Kuning', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10186.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('327', 'JEN00000', 'Farizol Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '230.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('328', 'JEN00000', 'Farmalat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '242.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('329', 'JEN00000', 'Farsycol Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3333.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('33', 'JEN00000', 'Antalgin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '154.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('330', 'JEN00000', 'Fasiprim Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3725.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('331', 'JEN00000', 'Fasiprim F Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '367.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('332', 'JEN00000', 'Fasidol Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '138.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('333', 'JEN00000', 'Freshcare Lavender (Ungu)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10184.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('334', 'JEN00000', 'Flucadex Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '445.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('335', 'JEN00000', 'Fludane Plus Sirup (Merah)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17876.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('336', 'JEN00000', 'Floxifar', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '417.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('337', 'JEN00000', 'Flucadex Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7950.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('338', 'JEN00000', 'Flutop-C Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4058.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('339', 'JEN00000', 'Flunarizin 10 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2090.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('34', 'JEN00000', 'Antangin Cair', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1867.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('340', 'JEN00000', 'Flunarizin 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1307.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('341', 'JEN00000', 'Fenofibrate 100 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2200.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('342', 'JEN00000', 'Freshcare Mix', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('343', 'JEN00000', 'Faktu Oint 20 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '108240.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('344', 'JEN00000', 'Faktu Supp', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('345', 'JEN00000', 'Gastrucid Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '251.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('346', 'JEN00000', 'Genoint Oint 15 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4522.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('347', 'JEN00000', 'Gentamicin 0,1%', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('348', 'JEN00000', 'Glibenklamid 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '97.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('349', 'JEN00000', 'Glucosamin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('35', 'JEN00000', 'Antasida Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '92.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('350', 'JEN00000', 'Glucophage 500', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1688.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('351', 'JEN00000', 'GOM', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2647.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('352', 'JEN00000', 'GPU Krim', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11301.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('353', 'JEN00000', 'Grafazol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('354', 'JEN00000', 'Grantusif', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '355.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('355', 'JEN00000', 'Guanistrep Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4444.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('356', 'JEN00000', 'Gralixa Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '150.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('357', 'JEN00000', 'Gention Violet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('358', 'JEN00000', 'Grathazon 0,5', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '108.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('359', 'JEN00000', 'Gabapentin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('36', 'JEN00000', 'Antasida Suspensi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3318.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('360', 'JEN00000', 'Genoint Tetes Mata', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7950.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('361', 'JEN00000', 'Genoint Salep Mata', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6800.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('362', 'JEN00000', 'Grazeo 20 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '159.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('363', 'JEN00000', 'Grafachlor', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '136.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('364', 'JEN00000', 'Grafalin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '96.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('365', 'JEN00000', 'Genalten Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2796.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('366', 'JEN00000', 'Gastrucid Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('367', 'JEN00000', 'Glimepirid 2 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1004.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('368', 'JEN00000', 'Gentalex Cr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2786.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('369', 'JEN00000', 'GPU Krim Jahe', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11979.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('37', 'JEN00000', 'Antimo Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('370', 'JEN00000', 'GPU Krim Pala', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('371', 'JEN00000', 'Griseofulvin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '368.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('372', 'JEN00000', 'Glimepiride 4 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2606.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('373', 'JEN00000', 'Glucovance', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2404.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('374', 'JEN00000', 'Hemaviton Cardio', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17079.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('375', 'JEN00000', 'Hemaviton Action', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5754.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('376', 'JEN00000', 'Hot In Aromatherapy 120 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14300.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('377', 'JEN00000', 'Hot In Cream 6o ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8444.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `ak_data_barang` (`id_barang`, `id_jenis`, `nm_barang`, `id_pabrik`, `id_kemasan`, `id_satuan`, `isi_satuan`, `golongan_obat`, `harga_dasar`, `margin`, `dosis`, `komposisi`, `indikasi`, `efek_samping`, `konsinyasi`, `formularium`, `stok_maksimum`, `stok_minimum`, `stok`, `deleted`) VALUES
('378', 'JEN00000', 'Hot In Cream 120 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14300.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('379', 'JEN00000', 'Hot In DCL 30 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('38', 'JEN00000', 'Apialys Drops 10 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '35211.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('380', 'JEN00000', 'Hot In DCL 60 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17325.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('381', 'JEN00000', 'Hufagesic Drop', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9166.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('382', 'JEN00000', 'Hufagrif Flu Sirup (Kuning)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('383', 'JEN00000', 'Hufagrif Pilek Sirup (Biru)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10011.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('384', 'JEN00000', 'Hufagrif B & P Sirup (Hijau)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('385', 'JEN00000', 'Hufamag Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '211.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('386', 'JEN00000', 'Hufamag Plus Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4590.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('387', 'JEN00000', 'Hydrocortisone 1%', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4475.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('388', 'JEN00000', 'Hydrocortisone 2,5%', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5363.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('389', 'JEN00000', 'Histigo', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '485.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('39', 'JEN00000', 'Apialys 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29343.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('390', 'JEN00000', 'Hufalysin 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8093.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('391', 'JEN00000', 'Hansaplast Roll (Kecil)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2937.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('392', 'JEN00000', 'Hansaplast Roll (Besar)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9746.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('393', 'JEN00000', 'Hansaplast Jumbo', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '512.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('394', 'JEN00000', 'Hansaplast Transparant', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5687.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('395', 'JEN00000', 'Hansaplast Biasa (Toples)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2787.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('396', 'JEN00000', 'Hufanoxyl Dry Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3888.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('397', 'JEN00000', 'Hufanoxyl Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '315.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('398', 'JEN00000', 'H-Booster Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13301.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('399', 'JEN00000', 'Herbakof sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10285.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('40', 'JEN00000', 'Asam Mefenamat (Hexp)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '176.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('400', 'JEN00000', 'Hufabetamine sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5024.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('401', 'JEN00000', 'Hufagrip Merah sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11630.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('402', 'JEN00000', 'Hypafix', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11999.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('403', 'JEN00000', 'H2O2 3% 100ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4596.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('404', 'JEN00000', 'Hanssaplast koyo Hangat rencen', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '839.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('405', 'JEN00000', 'Hansaplast disney (Gambar)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7083.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('406', 'JEN00000', 'Hufagesic tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '172.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('407', 'JEN00000', 'Herba vomits', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2926.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('408', 'JEN00000', 'Hot in Strong', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10395.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('409', 'JEN00000', 'ibuprofen 400mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '164.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('41', 'JEN00000', 'Asepso 80 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5665.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('410', 'JEN00000', 'Ichtyol salep', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3550.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('411', 'JEN00000', 'Ikadryl sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11906.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('412', 'JEN00000', 'Ika Gandapura 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('413', 'JEN00000', 'Imboost kids sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30293.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('414', 'JEN00000', 'Imunos sirup 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '58916.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('415', 'JEN00000', 'Imunos tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27170.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('416', 'JEN00000', 'incidal OD tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2720.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('417', 'JEN00000', 'Incidal OD sirup 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '35750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('418', 'JEN00000', 'Insto 7,5ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('419', 'JEN00000', 'Infussion adult', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8966.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('42', 'JEN00000', 'Aspilet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '571.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('420', 'JEN00000', 'Infussion ped', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15461.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('421', 'JEN00000', 'Interhistin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '647.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('422', 'JEN00000', 'Intunal forte tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2456.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('423', 'JEN00000', 'Intunal sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8166.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('424', 'JEN00000', 'Inza tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1650.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('425', 'JEN00000', 'irbesartan 300mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1248.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('426', 'JEN00000', 'Isosorbid', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '132.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('427', 'JEN00000', 'Itramol sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4111.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('428', 'JEN00000', 'Irgapan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '475.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('429', 'JEN00000', 'imboost force tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5610.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('43', 'JEN00000', 'Actifed Merah', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '44604.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('430', 'JEN00000', 'inzana tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '757.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('431', 'JEN00000', 'Inerson', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '46200.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('432', 'JEN00000', 'Imboost force sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '54450.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('433', 'JEN00000', 'Infantrim tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '226.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('434', 'JEN00000', 'Iremax tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9147.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('435', 'JEN00000', 'itrabat sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7700.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('436', 'JEN00000', 'irbesartan 150mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '544.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('437', 'JEN00000', 'Irbesartan 300mg (Hexparm)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5134.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('438', 'JEN00000', 'Igastrum sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('439', 'JEN00000', 'Kakak tua obat sakit gigi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8687.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('44', 'JEN00000', 'Actifed Hijau', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '44604.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('440', 'JEN00000', 'Kalium diklofenac', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '240.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('441', 'JEN00000', 'Kalcinol-N', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9405.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('442', 'JEN00000', 'Kalpanax 10cc', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2797.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('443', 'JEN00000', 'Kalpanax cream 5gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7044.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('444', 'JEN00000', 'Kapas Mawar', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3722.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('445', 'JEN00000', 'Kapsida', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10584.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('446', 'JEN00000', 'Keji beling', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3800.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('447', 'JEN00000', 'Ketoconazole cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('448', 'JEN00000', 'Ketoconazole 200mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '356.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('449', 'JEN00000', 'Komix herbal', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1751.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('45', 'JEN00000', 'Actifed Kuning', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '44076.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('450', 'JEN00000', 'Komix kids stw', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '759.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('451', 'JEN00000', 'Komix jeruk nipis', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '833.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('452', 'JEN00000', 'Koyo cabe', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('453', 'JEN00000', 'Kuldon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1644.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('454', 'JEN00000', 'Kotak p3k', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23434.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('455', 'JEN00000', 'Kandistatin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34650.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('456', 'JEN00000', 'Kasa steril', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3220.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('457', 'JEN00000', 'Kasa mami', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '888.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('458', 'JEN00000', 'Klmicetin cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10444.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('459', 'JEN00000', 'Kapas pembalut gulung 50gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3453.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('46', 'JEN00000', 'Acnol Lot', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10942.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('460', 'JEN00000', 'Kapas pembalut gulung 100gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6847.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('461', 'JEN00000', 'Kasa BB Hybird biru\'10 (kecil)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '980.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('462', 'JEN00000', 'Kompolax sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6144.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('463', 'JEN00000', 'Kenalog cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '53777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('464', 'JEN00000', 'koolfever bayi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4617.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('465', 'JEN00000', 'Kasa perban 2x10 (gunamed\'5)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('466', 'JEN00000', 'Kanna cream 30gr (hijau) white', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13200.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('467', 'JEN00000', 'Kanna cream 15gr (hijau)white', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7201.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('468', 'JEN00000', 'Knna cream 15gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6799.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('469', 'JEN00000', 'Kanna cream 15gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6799.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('47', 'JEN00000', 'Antimo Anak Sach', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '975.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('470', 'JEN00000', 'Komix jahe', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '833.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('471', 'JEN00000', 'Komix papermint', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '833.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('472', 'JEN00000', 'Kasa BB hybird biru\'5 (besar)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1947.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('473', 'JEN00000', 'Kapas wellness', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4548.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('474', 'JEN00000', 'Klorferson cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6450.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('475', 'JEN00000', 'Kunyit asem', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1200.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('476', 'JEN00000', 'Lactulax sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '45980.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('477', 'JEN00000', 'Lafalos cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13014.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('478', 'JEN00000', 'Lancar asi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7714.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('479', 'JEN00000', 'Lansoprazole 30mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1329.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('48', 'JEN00000', 'Atorvastatin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3573.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('480', 'JEN00000', 'Laserin madu 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4008.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('481', 'JEN00000', 'Laserin madu 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7918.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('482', 'JEN00000', 'Laserin syr 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3847.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('483', 'JEN00000', 'Laserin sirup 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7595.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('484', 'JEN00000', 'Licocalk 500mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '169.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('485', 'JEN00000', 'Lazol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '830.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('486', 'JEN00000', 'Lostacef', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '800.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('487', 'JEN00000', 'Listerin cool & mint 80ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7111.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('488', 'JEN00000', 'Listerin citrus 80ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7480.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('489', 'JEN00000', 'Laxing', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2378.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('49', 'JEN00000', 'Anabion Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4554.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('490', 'JEN00000', 'Larutan bedak dewasa (plain)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('491', 'JEN00000', 'Larutan kaki 3 anak (plain)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2473.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('492', 'JEN00000', 'Lerzin syr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4225.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('493', 'JEN00000', 'Librozym,', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '828.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('494', 'JEN00000', 'Lactacyd Fh (Dewasa)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20680.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('495', 'JEN00000', 'Lacto-B', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5940.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('496', 'JEN00000', 'Lerzin tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '388.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('497', 'JEN00000', 'Lapisiv tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1083.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('498', 'JEN00000', 'Lostacef sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7358.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('499', 'JEN00000', 'Lactamil stw', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('5', 'JEN00002', 'Abbocath No.20', 'PAB00002', 'KEM00001', 'SAT00001', 1, 'Generik', '15277.00', '0.00', '', '', '', '', 0, 0, 0, 0, 0, 0),
('50', 'JEN00000', 'Alleron', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '68.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('500', 'JEN00000', 'Lactamil coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('501', 'JEN00000', 'Lexigo tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '337.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('502', 'JEN00000', 'Listerine cool & mint 250ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18185.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('503', 'JEN00000', 'Listerine Original 250ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18370.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('504', 'JEN00000', 'Listerine Green tea 250ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20460.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('505', 'JEN00000', 'Laserin 110ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13721.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('506', 'JEN00000', 'Laxadine syr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21945.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('507', 'JEN00000', 'Levofloxacin tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '793.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('508', 'JEN00000', 'Listerin Fresh Brush 80ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7480.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('509', 'JEN00000', 'Lactamil Vanilla', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('51', 'JEN00000', 'Asifit Kaplet 30', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19886.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('510', 'JEN00000', 'Lifree XL (pax)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7443.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('511', 'JEN00000', 'Lifree L1 (renceng)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5949.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('512', 'JEN00000', 'Lifree M9 B', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5789.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('513', 'JEN00000', 'Lifree L8 B', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6513.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('514', 'JEN00000', 'Lactacyd Baby biru', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21890.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('515', 'JEN00000', 'Lamandel', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2167.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('516', 'JEN00000', 'Maagel 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4530.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('517', 'JEN00000', 'Madu Tj Joybe Jeruk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10107.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('518', 'JEN00000', 'Madu Tj Murni 150gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14144.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('519', 'JEN00000', 'Madu Tj Murni 250 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21687.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('52', 'JEN00000', 'Alphamol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '217.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('520', 'JEN00000', 'Medi-klin gel (orange)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22880.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('521', 'JEN00000', 'Mefinal 500mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1511.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('522', 'JEN00000', 'Meloxicam 7,5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '286.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('523', 'JEN00000', 'Meloxicam 15 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1199.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('524', 'JEN00000', 'Mertigo', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('525', 'JEN00000', 'Metformin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '136.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('526', 'JEN00000', 'Methylprednisolone 4mg (nulab)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '441.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('527', 'JEN00000', 'Methylprednisolone 16mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1283.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('528', 'JEN00000', 'metronidazole 500mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '225.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('529', 'JEN00000', 'M.Angin Kapak 3ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4637.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('53', 'JEN00000', 'Acyclovir 400', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '610.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('530', 'JEN00000', 'M.Kapak 5ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8204.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('531', 'JEN00000', 'M.Kapak 10ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12741.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('532', 'JEN00000', 'M.Kapak 14ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16713.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('533', 'JEN00000', 'Mepromagh tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('534', 'JEN00000', 'Microlax 5ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19079.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('535', 'JEN00000', 'Mirasic', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '187.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('536', 'JEN00000', 'Molexflu tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '378.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('537', 'JEN00000', 'M.k.p Lang 15ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4781.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('538', 'JEN00000', 'M.k.p Lang 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8686.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('539', 'JEN00000', 'M.k.p Lang 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16529.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('54', 'JEN00000', 'AnaKonidin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9431.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('540', 'JEN00000', 'M.k.p Lang 120 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '31093.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('541', 'JEN00000', 'M.Tawon DD', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('542', 'JEN00000', 'M.Tawon EE', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '36500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('543', 'JEN00000', 'M.Tawon FF', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '40146.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('544', 'JEN00000', 'M.Telon lang plus 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7656.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('545', 'JEN00000', 'M.Telon lang plus 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14868.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('546', 'JEN00000', 'M.Telon konicare plus 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12474.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('547', 'JEN00000', 'M.Urut Gpu 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12570.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('548', 'JEN00000', 'M.Urut Gpu 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7284.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('549', 'JEN00000', 'M.Telon meneer 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10605.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('55', 'JEN00000', 'Anlene Actifit Coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('550', 'JEN00000', 'Mucera paed syr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18810.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('551', 'JEN00000', 'Mylanta syr 50ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10994.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('552', 'JEN00000', 'Mylanta tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5198.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('553', 'JEN00000', 'Momilen diapers 15gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15151.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('554', 'JEN00000', 'Momilen Nursing 15gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19999.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('555', 'JEN00000', 'Mixagrip flu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1840.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('556', 'JEN00000', 'Miconazole cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3700.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('557', 'JEN00000', 'M.K.P Konicare 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10966.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('558', 'JEN00000', 'M.K.P Konicare 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18550.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('559', 'JEN00000', 'My Baby Telon 60ml 6 jam', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15931.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('56', 'JEN00000', 'Anlene Gold Coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('560', 'JEN00000', 'My Baby Telon plus 60ml 8 jam', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15931.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('561', 'JEN00000', 'My Baby Telon plus 90ml 8 jam', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('562', 'JEN00000', 'Masker Hijab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '501.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('563', 'JEN00000', 'Milna Toddler', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12033.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('564', 'JEN00000', 'Mycoral tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3825.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('565', 'JEN00000', 'Mycoral cream 5gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('566', 'JEN00000', 'Masker tali', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '380.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('567', 'JEN00000', 'Masker karet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '458.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('568', 'JEN00000', 'Mecobalamin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '770.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('569', 'JEN00000', 'Myk Gandapura Lang 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6633.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('57', 'JEN00000', 'Abbocath No.24', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14819.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('570', 'JEN00000', 'Methylprednisolone 8mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '461.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('571', 'JEN00000', 'Molagit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '480.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('572', 'JEN00000', 'Mamypoko Pants stand M9', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15751.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('573', 'JEN00000', 'Mamypoko Pants S1 (renceng)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1321.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('574', 'JEN00000', 'Mamypoko Pants L1 (renceng)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('575', 'JEN00000', 'Mamypoko Pants Stand L8', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15908.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('576', 'JEN00000', 'Mamypoko Pants M1 (renceng)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1760.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('577', 'JEN00000', 'Mamypoko Pants stand S11', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15751.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('578', 'JEN00000', 'Mamypoko Open stand M10', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16016.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('579', 'JEN00000', 'Methylprednisolone 4mg (MBF)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '263.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('58', 'JEN00000', 'Asam Traneksamat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1626.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('580', 'JEN00000', 'Molapect tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '556.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('581', 'JEN00000', 'Myk Sereh Dragon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('582', 'JEN00000', 'Myco-Z', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '73333.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('583', 'JEN00000', 'Mylanta sirup 150ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '33594.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('584', 'JEN00000', 'Maximus Dietry Herba Kap\'30', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14374.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('585', 'JEN00000', 'Maagmeta plus sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3080.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('586', 'JEN00000', 'My Baby Telon plus 6 jam 90ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('587', 'JEN00000', 'Molafate suspensi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11616.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('588', 'JEN00000', 'Madu rasa sachet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '766.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('589', 'JEN00000', 'Melanox cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29315.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('59', 'JEN00000', 'Anacetin Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5929.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('590', 'JEN00000', 'Medi-klin TR ungu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '39053.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('591', 'JEN00000', 'Mixagrip Flu&Batuk (hijau)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('592', 'JEN00000', 'Micropore 1/2 3M', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14501.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('593', 'JEN00000', 'Minyak telon Lang 100ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22130.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('594', 'JEN00000', 'Mucopect drop', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '56763.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('595', 'JEN00000', 'Madu Tj joybe stawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10107.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('596', 'JEN00000', 'Mometason cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14107.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('597', 'JEN00000', 'Madu Tj Joybe original', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10107.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('598', 'JEN00000', 'Myk otot Geliga 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11887.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('599', 'JEN00000', 'Minyak ikan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '98.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('6', 'JEN00002', 'Abbocath no. 22', 'PAB00080', 'KEM00001', 'SAT00001', 1, 'Generik', '14819.00', '0.00', '', '', '', '', 0, 0, 0, 0, 0, 0),
('60', 'JEN00000', 'Akurat Test', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8772.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('600', 'JEN00000', 'Mucos sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12320.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('601', 'JEN00000', 'Madu nusa jeruk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('602', 'JEN00000', 'Madu nusa strawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('603', 'JEN00000', 'Minyak angin cap lang', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('604', 'JEN00000', 'M.K.P Lang greentea', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8952.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('605', 'JEN00000', 'Natrium diklofenak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '299.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('606', 'JEN00000', 'Natur-e Kapsul\'16', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16316.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('607', 'JEN00000', 'neurobion biru', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14538.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('608', 'JEN00000', 'Neurobion Forte', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '26761.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('609', 'JEN00000', 'Neuralgin Rx', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '644.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('61', 'JEN00000', 'Alkohol Swab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '151.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('610', 'JEN00000', 'Neurodex', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '508.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('611', 'JEN00000', 'Neo rheumacyl cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13525.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('612', 'JEN00000', 'Neo rheumacyl hot', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7575.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('613', 'JEN00000', 'Neozep-F', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2240.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('614', 'JEN00000', 'New Diatab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1986.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('615', 'JEN00000', 'Nifudiar sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '35000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('616', 'JEN00000', 'Nitasan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10800.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('617', 'JEN00000', 'Nisagon cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4338.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('618', 'JEN00000', 'Novadiar 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3396.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('619', 'JEN00000', 'Neo entrostop tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5269.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('62', 'JEN00000', 'Baby Cough Uni', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3685.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('620', 'JEN00000', 'Neo Napacin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1726.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('621', 'JEN00000', 'Nutrive benecol (black)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5818.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('622', 'JEN00000', 'Nutrive benecol (stw)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5818.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('623', 'JEN00000', 'New Diaform', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '205.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('624', 'JEN00000', 'Nourish skin-15', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7912.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('625', 'JEN00000', 'Norit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12281.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('626', 'JEN00000', 'Neo rheumacyl neuro', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8708.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('627', 'JEN00000', 'Norelut', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4311.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('628', 'JEN00000', 'Nourish skin ultimate\'15', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8711.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('629', 'JEN00000', 'Needle 23G', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '820.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('63', 'JEN00000', 'Balsem Lang No. 1 20 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7192.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('630', 'JEN00000', 'NACL', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('631', 'JEN00000', 'New Astar 15gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6976.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('632', 'JEN00000', 'Natur-E Advance', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8776.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('633', 'JEN00000', 'Natur-E 300UI Pink\'16', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7892.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('634', 'JEN00000', 'Natur-E Lotion pink 100ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12608.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('635', 'JEN00000', 'Natur-E Lotion pink 245ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25295.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('636', 'JEN00000', 'Natur-E Lotion Advance 245', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('637', 'JEN00000', 'Natur-E Lotion Advance 100', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('638', 'JEN00000', 'Natur-E Lotion Hijau 245', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21995.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('639', 'JEN00000', 'Natur-E Lotion Hijau 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11238.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('64', 'JEN00000', 'Balsem Geliga 10 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4045.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('640', 'JEN00000', 'Neo Rheumacyl Cream Merah 30 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12362.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('641', 'JEN00000', 'Ninio 3-2103', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('642', 'JEN00000', 'Ninio 4101', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('643', 'JEN00000', 'Ninio 2061', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('644', 'JEN00000', 'Ninio 1601', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('645', 'JEN00000', 'Ninio 1001', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('646', 'JEN00000', 'Ninio 1002', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('647', 'JEN00000', 'Ninio 1003', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('648', 'JEN00000', 'Ninio Tempat Bedak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('649', 'JEN00000', 'Ninio 6001', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('65', 'JEN00000', 'Balsem Geliga 20 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7414.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('650', 'JEN00000', 'Ninio F-338', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('651', 'JEN00000', 'Ninio 2001', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('652', 'JEN00000', 'Ninio 6501', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('653', 'JEN00000', 'Ninio Tempat Bedak (Kecil)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('654', 'JEN00000', 'Ninio F-324', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('655', 'JEN00000', 'Ninio 5009', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('656', 'JEN00000', 'Ninio Tempat Susu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('657', 'JEN00000', 'Ninio Dot Karet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2778.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('658', 'JEN00000', 'Ninio Breast Pump', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('659', 'JEN00000', 'Ninio Baby Gift Set', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('66', 'JEN00000', 'Batugin Elixir 120 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20584.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('660', 'JEN00000', 'OBH Itrasal', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('661', 'JEN00000', 'OBP Itrasal', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('662', 'JEN00000', 'OBH Combi Original', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9433.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('663', 'JEN00000', 'Obimin Af', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14185.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('664', 'JEN00000', 'Ondansentron 4 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '962.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('665', 'JEN00000', 'Omeprazol 30 mg (Hexp)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '311.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('666', 'JEN00000', 'Oralit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '421.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('667', 'JEN00000', 'Oskadon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1533.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('668', 'JEN00000', 'Oxytetracycline 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('669', 'JEN00000', 'Om3 Heart', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32670.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('67', 'JEN00000', 'Bedak Herocyn 85 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10450.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('670', 'JEN00000', 'Obat Batuk Ibu Dan Anak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19493.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('671', 'JEN00000', 'OBH Combi B/F Dewasa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('672', 'JEN00000', 'OBH Combi B/F Anak Orange', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10670.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('673', 'JEN00000', 'OBH Combi Anak B/F Strawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10670.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `ak_data_barang` (`id_barang`, `id_jenis`, `nm_barang`, `id_pabrik`, `id_kemasan`, `id_satuan`, `isi_satuan`, `golongan_obat`, `harga_dasar`, `margin`, `dosis`, `komposisi`, `indikasi`, `efek_samping`, `konsinyasi`, `formularium`, `stok_maksimum`, `stok_minimum`, `stok`, `deleted`) VALUES
('674', 'JEN00000', 'OBH Combi Anak B/F Apel', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10670.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('675', 'JEN00000', 'OBH Nelco 55 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20321.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('676', 'JEN00000', 'OBH Nelco 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25634.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('677', 'JEN00000', 'Omepros', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '121440.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('678', 'JEN00000', 'Otopain Drops TT', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '46250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('679', 'JEN00000', 'Omedrinat Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '180.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('68', 'JEN00000', 'Bedak Herocyn Baby 100 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6242.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('680', 'JEN00000', 'Omeneuron', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '375.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('681', 'JEN00000', 'Omegesic', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '285.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('682', 'JEN00000', 'Oskadon SP', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1567.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('683', 'JEN00000', 'Pacdin Cough', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3630.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('684', 'JEN00000', 'Pagoda Salep', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3613.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('685', 'JEN00000', 'Panadol Biru', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6673.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('686', 'JEN00000', 'Panadol Extra', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7361.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('687', 'JEN00000', 'Panadol Flu & Batuk (Hijau)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8852.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('688', 'JEN00000', 'Panadol Sirup 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '33778.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('689', 'JEN00000', 'Panadol Sirup 30 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19172.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('69', 'JEN00000', 'Bedak Salicyl 2% KF', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5857.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('690', 'JEN00000', 'Panadol Drops 15 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38667.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('691', 'JEN00000', 'Panadol Chew (Anak)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('692', 'JEN00000', 'Paracetamol 500 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '94.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('693', 'JEN00000', 'Paraflu Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('694', 'JEN00000', 'Paratusin Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19723.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('695', 'JEN00000', 'Paratusin Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7578.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('696', 'JEN00000', 'Parcok', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5655.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('697', 'JEN00000', 'Pedialyte Liquid', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27955.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('698', 'JEN00000', 'Pedialyte Buble Gum', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29833.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('699', 'JEN00000', 'Pil Kb Andalan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4465.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('7', 'JEN00000', 'Acifar Cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Non Generik', '4565.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('70', 'JEN00000', 'Bedak Salicyl Sach', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2038.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('700', 'JEN00000', 'Pil Kb Andalan Laktasi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('701', 'JEN00000', 'Pil Kb Microgynon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12115.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('702', 'JEN00000', 'Pil Kb Planotab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3376.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('703', 'JEN00000', 'Piroxicam 10 mg (Kapsul)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '196.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('704', 'JEN00000', 'piroxicam 20 mg (Dexa)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '140.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('705', 'JEN00000', 'Pispot Sodok', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38966.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('706', 'JEN00000', 'Plantacid Forte Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7990.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('707', 'JEN00000', 'Plantacid Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9145.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('708', 'JEN00000', 'Plantacid Forte Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '26730.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('709', 'JEN00000', 'Praxion Sirup Orange', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20377.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('71', 'JEN00000', 'Bedak Salicyl IKA', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('710', 'JEN00000', 'Promaag', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5309.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('711', 'JEN00000', 'Promaag Gazero', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1638.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('712', 'JEN00000', 'Ponstan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2309.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('713', 'JEN00000', 'Prednison (Trifacort)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '138.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('714', 'JEN00000', 'Peditox', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6105.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('715', 'JEN00000', 'Pilkita', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1274.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('716', 'JEN00000', 'Proris Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7001.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('717', 'JEN00000', 'Proris Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23048.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('718', 'JEN00000', 'Proris Forte Sirup (Biru)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '26462.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('719', 'JEN00000', 'Polysilane Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7123.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('72', 'JEN00000', 'Bedak Salicyl IKA (menthol)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12914.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('720', 'JEN00000', 'Polysilane Sirup 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19842.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('721', 'JEN00000', 'Polysilane Sirup 180 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('722', 'JEN00000', 'Pampers Saudi M', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4673.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('723', 'JEN00000', 'Pampers Supreme', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5641.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('724', 'JEN00000', 'Pampers Saudi L', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5190.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('725', 'JEN00000', 'Pampers Saudi XL', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6323.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('726', 'JEN00000', 'Polident A 60 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '41711.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('727', 'JEN00000', 'Polident A 15 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13888.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('728', 'JEN00000', 'Paramex', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1779.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('729', 'JEN00000', 'Plester Fancy', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1666.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('73', 'JEN00000', 'Betadin F. Hygiene 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20914.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('730', 'JEN00000', 'Prenage Mom Emesis', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '40900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('731', 'JEN00000', 'Prenagen Momy Coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38868.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('732', 'JEN00000', 'Pharmaton Formula', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4098.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('733', 'JEN00000', 'Pronicy', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '228.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('734', 'JEN00000', 'Propepsa  Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '49500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('735', 'JEN00000', 'Perban Coklat\'3 (Kecil)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('736', 'JEN00000', 'Perban Coklat\'4 (Sedang)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15999.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('737', 'JEN00000', 'Primolut', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5067.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('738', 'JEN00000', 'Praxion Forte Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25080.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('739', 'JEN00000', 'Paracetamol Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2310.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('74', 'JEN00000', 'Betadin Kumur 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10238.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('740', 'JEN00000', 'Pharolit', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1084.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('741', 'JEN00000', 'Prenagen Esensis Coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30947.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('742', 'JEN00000', 'Pi Kang Shuang', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('743', 'JEN00000', 'Propanolol 10 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '85.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('744', 'JEN00000', 'Propanolol  40 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '135.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('745', 'JEN00000', 'Pil Sakit Perut', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1934.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('746', 'JEN00000', 'Purbasari Lulur Mandi 125 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6879.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('747', 'JEN00000', 'Purbasarari Manjakani', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6304.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('748', 'JEN00000', 'Purbasari Lotion 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5658.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('749', 'JEN00000', 'Purbasari Lotion 120 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10102.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('75', 'JEN00000', 'Betadin Oint 5 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8894.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('750', 'JEN00000', 'Purbasari Lotion Zaitun 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6801.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('751', 'JEN00000', 'Pampers Dr.P M10', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6494.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('752', 'JEN00000', 'Purbasari Sabun Bengkoang', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('753', 'JEN00000', 'Purbasari Sabun Zaitun', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('754', 'JEN00000', 'Procold', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('755', 'JEN00000', 'Polycrol Forte Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '24750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('756', 'JEN00000', 'PeKa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3575.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('757', 'JEN00000', 'Puyer Bintang 7', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '465.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('758', 'JEN00000', 'Prostakur', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15243.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('759', 'JEN00000', 'Pirocam 20 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '186.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('76', 'JEN00000', 'Betadin Sol 5 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3365.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('760', 'JEN00000', 'Prenagen Mom Emesis Vanilla', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '40900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('761', 'JEN00000', 'Prenagen Momy Vanilla', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '38868.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('762', 'JEN00000', 'Purbasari Lulur Mandi 235 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('763', 'JEN00000', 'Pipet Kaca', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1051.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('764', 'JEN00000', 'Pipet Drop', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3300.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('765', 'JEN00000', 'Pasaba Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3410.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('766', 'JEN00000', 'Ranitidin (Erela)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '150.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('767', 'JEN00000', 'Rivanol 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2536.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('768', 'JEN00000', 'Rohto Eye Plus', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '26546.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('769', 'JEN00000', 'Rohto Cool', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13920.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('77', 'JEN00000', 'Betadin Sol 15 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9551.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('770', 'JEN00000', 'Renabetic 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '102.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('771', 'JEN00000', 'Redoxon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32890.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('772', 'JEN00000', 'Reco Tetes Mata', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('773', 'JEN00000', 'Renadinac 50 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '241.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('774', 'JEN00000', 'Rifampicin 450 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1145.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('775', 'JEN00000', 'Ramabion', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '235.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('776', 'JEN00000', 'Roverton Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '147.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('777', 'JEN00000', 'Rohto Biasa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9876.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('778', 'JEN00000', 'Ringer Laktat (RL)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('779', 'JEN00000', 'Sabun JF Acne Care', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9020.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('78', 'JEN00000', 'Betadin Sol 30 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17606.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('780', 'JEN00000', 'Sabun Papaya BDL 60 g', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4614.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('781', 'JEN00000', 'Sabun Papaya BDL Transparan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4712.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('782', 'JEN00000', 'Sakatonik Liver 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8447.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('783', 'JEN00000', 'Sakatonik ABC Grape', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11120.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('784', 'JEN00000', 'Sakatonik ABC Antariksa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11674.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('785', 'JEN00000', 'Sakatonik ABC Orange', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('786', 'JEN00000', 'Salbutamol 2 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '85.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('787', 'JEN00000', 'Salonpas Hot', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4832.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('788', 'JEN00000', 'Salonpas Super', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4903.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('789', 'JEN00000', 'Saltrim Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4888.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('79', 'JEN00000', 'Betadin Sol 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '31801.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('790', 'JEN00000', 'Saltrim Forte Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6333.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('791', 'JEN00000', 'Sangobion\'4', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4184.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('792', 'JEN00000', 'Sanmol Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11825.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('793', 'JEN00000', 'Sanmol Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1144.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('794', 'JEN00000', 'Sanmol Drop', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15895.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('795', 'JEN00000', 'Siladex Biru 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8599.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('796', 'JEN00000', 'Siladex ME 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8831.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('797', 'JEN00000', 'Siladex ME 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6006.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('798', 'JEN00000', 'Silex Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '42624.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('799', 'JEN00000', 'Simvastatin 10mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '189.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('8', 'JEN00000', 'Acyclovir Cream 5 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2690.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('80', 'JEN00000', 'Betason Cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8708.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('800', 'JEN00000', 'Simvastatin 20mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '693.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('801', 'JEN00000', 'Simvastatin 20mg (Quantum)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '440.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('802', 'JEN00000', 'Solinfec cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5260.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('803', 'JEN00000', 'Strepsil Cool', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5560.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('804', 'JEN00000', 'Strepsil H Lemon', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7340.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('805', 'JEN00000', 'Suntikan 5cc', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('806', 'JEN00000', 'Sutra OK', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4950.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('807', 'JEN00000', 'Sutra 24b pack (Merah)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3400.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('808', 'JEN00000', 'Sutra RM', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4250.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('809', 'JEN00000', 'S.Tangan steril 7,5', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5555.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('81', 'JEN00000', 'Betason-N Cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10420.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('810', 'JEN00000', 'Synalten cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('811', 'JEN00000', 'Salep-24', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3177.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('812', 'JEN00000', 'Scoot emulsion Vita', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25391.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('813', 'JEN00000', 'Sensitif Test kehamilan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20484.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('814', 'JEN00000', 'Salep 88', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7865.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('815', 'JEN00000', 'Stimuno sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21112.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('816', 'JEN00000', 'Selvim 10mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '308.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('817', 'JEN00000', 'Selvim 20mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '430.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('818', 'JEN00000', 'Stop Cold Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2644.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('819', 'JEN00000', 'Super Tetra', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '981.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('82', 'JEN00000', 'Bevalex Cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8725.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('820', 'JEN00000', 'Sarung Tangan (M)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '484.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('821', 'JEN00000', 'Sarung Tangan (s)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '558.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('822', 'JEN00000', 'Superhoid', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3938.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('823', 'JEN00000', 'Sabun JF Family', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8470.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('824', 'JEN00000', 'Suntikan 3cc', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1065.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('825', 'JEN00000', 'Scabimite cr 10gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '39188.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('826', 'JEN00000', 'Sabun Dettol Active', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2956.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('827', 'JEN00000', 'Sabun Dettol Sensitif', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3116.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('828', 'JEN00000', 'Sabun Dettol Cool', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2956.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('829', 'JEN00000', 'Sabun Dettol Original', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3115.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('83', 'JEN00000', 'Biogesik', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1568.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('830', 'JEN00000', 'Spasminal tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '472.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('831', 'JEN00000', 'Safe care', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13464.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('832', 'JEN00000', 'SGM 0-6 bln 120gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('833', 'JEN00000', 'SGM 6-12 bln 120gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('834', 'JEN00000', 'SGM 1+ 150gr Madu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('835', 'JEN00000', 'SGM 1+ 150gr Vanilla', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('836', 'JEN00000', 'SGM LLM', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('837', 'JEN00000', 'SGM Bunda Coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('838', 'JEN00000', 'SGM Bunda Jeruk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('839', 'JEN00000', 'SGM Bunda Strawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('84', 'JEN00000', 'Biolysin Smart 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10277.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('840', 'JEN00000', 'Susu Dancow 0-6 bln', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('841', 'JEN00000', 'Salbutamol 4mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '130.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('842', 'JEN00000', 'Salonpas gel', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10252.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('843', 'JEN00000', 'Sangobion isi\'10', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10674.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('844', 'JEN00000', 'SGM 6-12 Bln 400gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34950.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('845', 'JEN00000', 'SGM 3+ Madu 120gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12600.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('846', 'JEN00000', 'Silet cukur Gold', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('847', 'JEN00000', 'Stimuno Sirup grape', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20350.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('848', 'JEN00000', 'SGM 3+ Vanilla 400gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '33650.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('849', 'JEN00000', 'SGM 3+ Madu 400 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '33700.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('85', 'JEN00000', 'Biolysin Sirup 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10684.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('850', 'JEN00000', 'Siladex Biru 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6006.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('851', 'JEN00000', 'SGM BBLR', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32065.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('852', 'JEN00000', 'SGM Soya 200 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '28100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('853', 'JEN00000', 'Stimuno Forte Tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2352.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('854', 'JEN00000', 'Sangobion Femine', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9964.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('855', 'JEN00000', 'Sangobion Vitatonik', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '21563.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('856', 'JEN00000', 'Sari Kurma Aljazira', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('857', 'JEN00000', 'Syamil Anak (Buah hati)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('858', 'JEN00000', 'Syamil Family', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('859', 'JEN00000', 'Sakatonik ABC Strawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11674.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('86', 'JEN00000', 'Bioplacenton', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13672.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('860', 'JEN00000', 'Salonpas Gel Patch', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12936.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('861', 'JEN00000', 'Supravit\'60', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25518.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('862', 'JEN00000', 'Supravit Tablet (strip)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4289.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('863', 'JEN00000', 'Tantum Verde 120ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34774.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('864', 'JEN00000', 'Tempra Forte syr 60 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '39270.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('865', 'JEN00000', 'Teosal', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '146.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('866', 'JEN00000', 'Tera-F', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '244.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('867', 'JEN00000', 'Termorex plus', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11085.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('868', 'JEN00000', 'Termorex sirup 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7292.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('869', 'JEN00000', 'Termorex sirup 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10396.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('87', 'JEN00000', 'Bisolvon Ekstra', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '34105.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('870', 'JEN00000', 'Thiamfenicol 500mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1094.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('871', 'JEN00000', 'Thrombopob Gel 20gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '50936.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('872', 'JEN00000', 'Tissu Green\'200', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('873', 'JEN00000', 'Tisu facial box tupai', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('874', 'JEN00000', 'Tonikum Bayer 100 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12463.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('875', 'JEN00000', 'Tonikum Bayer 330 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '24677.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('876', 'JEN00000', 'Tolak angin cair anak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1630.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('877', 'JEN00000', 'Tolak angin cair Dewasa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2292.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('878', 'JEN00000', 'Triocid tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '177.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('879', 'JEN00000', 'Triocid sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4373.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('88', 'JEN00000', 'Bisoprolol', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '777.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('880', 'JEN00000', 'Tempra drops', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '42570.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('881', 'JEN00000', 'Test pack One made', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1320.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('882', 'JEN00000', 'Thermolyte sugar\'25', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17248.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('883', 'JEN00000', 'Transfulmin BB 10gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '42570.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('884', 'JEN00000', 'Trianta tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '173.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('885', 'JEN00000', 'Trimenta sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3222.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('886', 'JEN00000', 'Tisu mittu 10\'s', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3116.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('887', 'JEN00000', 'Tisu mittu 20\'s', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4495.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('888', 'JEN00000', 'Thrombo Gel 10 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '33957.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('889', 'JEN00000', 'Tisu Mittu Biru\'40', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6804.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('89', 'JEN00000', 'Bodrex', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3106.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('890', 'JEN00000', 'Triaminic Batuk & Pilek', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '53676.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('891', 'JEN00000', 'Triaminic Pilek', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '48708.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('892', 'JEN00000', 'Test pack Serenity', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1351.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('893', 'JEN00000', 'Tay pin San', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('894', 'JEN00000', 'Tissu Mittu Pink\'40', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6874.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('895', 'JEN00000', 'Tissu Mittu Hijau\'50', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '14233.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('896', 'JEN00000', 'Tisu Green\'40 kecil', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('897', 'JEN00000', 'Thermometer Elastis', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '24750.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('898', 'JEN00000', 'Termorex Plus 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7436.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('899', 'JEN00000', 'Tempat bedak doble', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16700.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('9', 'JEN00006', 'Ademsari', 'PAB00000', 'KEM00001', 'SAT00009', 100, 'Generik', '1479.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('90', 'JEN00000', 'Bodrexin Tab', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1108.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('900', 'JEN00000', 'Tempra sirup 30ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18590.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('901', 'JEN00000', 'Tempra sirup 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '36000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('902', 'JEN00000', 'Tolak angin flu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2330.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('903', 'JEN00000', 'Tolak Linu biasa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('904', 'JEN00000', 'Tolak angin linu mint', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('905', 'JEN00000', 'Tolak angin Permen', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1430.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('906', 'JEN00000', 'Tissu travel paseo pack', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9000.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('907', 'JEN00000', 'Upixon sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11757.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('908', 'JEN00000', 'Urinal wanita', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4445.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('909', 'JEN00000', 'Ultracillin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7292.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('91', 'JEN00000', 'Bodrexin Flu & Batuk Sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '7524.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('910', 'JEN00000', 'Urine Bag', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4593.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('911', 'JEN00000', 'Urdafalk', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10320.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('912', 'JEN00000', 'Ultraflu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2469.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('913', 'JEN00000', 'Urinal laki', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4445.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('914', 'JEN00000', 'Vegeta Herbal anggur', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2100.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('915', 'JEN00000', 'Vesperum drop', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9444.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('916', 'JEN00000', 'Vesperum sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3825.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('917', 'JEN00000', 'Viostin DS', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5900.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('918', 'JEN00000', 'Visine original TM', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12892.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('919', 'JEN00000', 'Vitalong-C', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4523.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('92', 'JEN00000', 'Bodrex Migra', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1735.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('920', 'JEN00000', 'Vitacimin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1348.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('921', 'JEN00000', 'Vit-C IPI', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3922.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('922', 'JEN00000', 'Vit-B1 IPI', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3992.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('923', 'JEN00000', 'Vit-B12 IPI', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3922.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('924', 'JEN00000', 'Vit-B.komplex IPI', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3922.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('925', 'JEN00000', 'Vicks inhaler', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12703.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('926', 'JEN00000', 'Vicks F44 anak 54ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5757.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('927', 'JEN00000', 'Vicks F44 anak 54ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10706.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('928', 'JEN00000', 'Vicks F44 dewasa 27ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5789.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('929', 'JEN00000', 'Vicks F44 dewasa 54ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10761.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('93', 'JEN00000', 'Blood Set', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '22417.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('930', 'JEN00000', 'Vicks vaporub 10gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6487.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('931', 'JEN00000', 'Vicks vaporub 25 gr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13681.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('932', 'JEN00000', 'Voltadex 50mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '268.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('933', 'JEN00000', 'Vegeblend 21', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '106722.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('934', 'JEN00000', 'Vital ear oil', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12781.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('935', 'JEN00000', 'Veril Acne gel', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13236.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('936', 'JEN00000', 'Voltaren Gel', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '23917.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('937', 'JEN00000', 'Vosea tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '154.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('938', 'JEN00000', 'Vidoran smart', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '11454.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('939', 'JEN00000', 'Voltaren tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6110.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('94', 'JEN00000', 'BreastPum Mami', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '10500.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('940', 'JEN00000', 'Vitamin.B.komplex (KF)', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '133.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('941', 'JEN00000', 'Ventolin inhaler', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '99659.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('942', 'JEN00000', 'Ventolin nebule', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9492.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('943', 'JEN00000', 'Vosea syr', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4050.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('944', 'JEN00000', 'Vit-A ipi', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3922.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('945', 'JEN00000', 'Vitazym', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '983.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('946', 'JEN00000', 'Vitalong-C Pot', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32560.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('947', 'JEN00000', 'Venaron', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '2748.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('948', 'JEN00000', 'Waisan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '5150.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('949', 'JEN00000', 'Woods cough ATT 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13860.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('95', 'JEN00000', 'Breast Pad\'10', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1833.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('950', 'JEN00000', 'Woods cough Exp 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15018.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('951', 'JEN00000', 'Woods Herbal 60ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '12752.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('952', 'JEN00000', 'Y-Rins120 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '25300.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('953', 'JEN00000', 'Yusimox sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3325.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('954', 'JEN00000', 'Yusimox F sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4875.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('955', 'JEN00000', 'Yusimox tablet', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '343.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('956', 'JEN00000', 'Zenirex sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4333.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('957', 'JEN00000', 'Zevask 5 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '345.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('958', 'JEN00000', 'Zinc 20 mg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '350.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('959', 'JEN00000', 'Zinkid sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '27225.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('96', 'JEN00000', 'Breastpump Reg', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16111.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('960', 'JEN00000', 'Zendalat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '293.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('961', 'JEN00000', 'Zetamol sirup', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '3325.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('962', 'JEN00000', 'Sari Kurma Azzahra', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('963', 'JEN00000', 'Sari Kurma Kafilah B', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '40000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('964', 'JEN00000', 'Sum Cream', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('965', 'JEN00000', 'Minyak Zaitun', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '18888.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('966', 'JEN00000', 'Madu Kurma Manggis', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '15000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('967', 'JEN00000', 'Madu Hitam plus Propolis', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '66111.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('968', 'JEN00000', 'Madu Hitam 175', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30666.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('969', 'JEN00000', 'G-Bumin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '71944.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('97', 'JEN00000', 'Bronchitin Expect 50 ml', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4775.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0);
INSERT INTO `ak_data_barang` (`id_barang`, `id_jenis`, `nm_barang`, `id_pabrik`, `id_kemasan`, `id_satuan`, `isi_satuan`, `golongan_obat`, `harga_dasar`, `margin`, `dosis`, `komposisi`, `indikasi`, `efek_samping`, `konsinyasi`, `formularium`, `stok_maksimum`, `stok_minimum`, `stok`, `deleted`) VALUES
('970', 'JEN00000', 'Virgin OIl', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '29333.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('971', 'JEN00000', 'Gamet SAR\'30', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '52937.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('972', 'JEN00000', 'Vermin isi 12', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19450.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('973', 'JEN00000', 'Vermin isi 30', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '37888.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('974', 'JEN00000', 'Gamet SAR 60', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '90475.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('975', 'JEN00000', 'Habattusauda Inolife 100', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '33055.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('976', 'JEN00000', 'Habattusauda Inolife 210', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '56666.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('977', 'JEN00000', 'Madu Murni', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '52144.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('978', 'JEN00000', 'Susu Kedelai Mandala', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '31000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('979', 'JEN00000', 'Syamil Anak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '16000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('98', 'JEN00000', 'Bye-Bye Fever Baby', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '6126.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('980', 'JEN00000', 'Sari Kurma Al-Jazira', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '19000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('981', 'JEN00000', 'Sky Goat Susu Kambing', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '30000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('982', 'JEN00000', 'Teh Daun Jati Cina', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '9000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('983', 'JEN00000', 'Vitabumin', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '60000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('984', 'JEN00000', 'Sari Kurma Sahara', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17500.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('985', 'JEN00000', 'Syamil Keluarga', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '20000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('986', 'JEN00000', 'Zahra Platinum', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '17500.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('987', 'JEN00000', 'Hydrococo Original', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4598.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('988', 'JEN00000', 'Komix Lo Han Kuo', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1751.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('989', 'JEN00000', 'Komix Herbal', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '1751.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('99', 'JEN00000', 'Bye-Bye Fever Child', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8722.00', '0.00', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0),
('990', 'JEN00000', 'Chil-Kid 0-3 Madu', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32340.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('991', 'JEN00000', 'Chil Kid 0-3 Vanila', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '32340.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('992', 'JEN00000', 'Nutri Joss Dewasa', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '80000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('993', 'JEN00000', 'Nutri Joss Anak', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '50000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('994', 'JEN00000', 'Madu SP', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '45000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('995', 'JEN00000', 'Susu Zee Stawberry', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4620.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('996', 'JEN00000', 'Susu Zee Coklat', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '4620.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('997', 'JEN00000', 'Minyak Wangi HBS', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('998', 'JEN00000', 'Myk Wangi Ar-Rayyan', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '8000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0),
('999', 'JEN00000', 'Jaguar', 'PAB00000', 'KEM00000', 'SAT0000', 100, 'Generik', '13000.00', '0.00', NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_barang_detail`
--

CREATE TABLE `ak_data_barang_detail` (
  `id_detail_barang` bigint(20) NOT NULL,
  `id_pembelian` char(50) DEFAULT NULL,
  `id_barang` bigint(20) NOT NULL,
  `batch` char(25) DEFAULT NULL,
  `kadaluarsa` date DEFAULT NULL,
  `stok_tersedia` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_barang_detail`
--

INSERT INTO `ak_data_barang_detail` (`id_detail_barang`, `id_pembelian`, `id_barang`, `batch`, `kadaluarsa`, `stok_tersedia`, `deleted`) VALUES
(2, NULL, 6, 'EEEE', '2019-04-14', 100, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_barang_stok_keluar`
--

CREATE TABLE `ak_data_barang_stok_keluar` (
  `id_stok_barang_keluar` bigint(20) NOT NULL,
  `id_barang` bigint(20) NOT NULL,
  `id_penjualan` char(20) DEFAULT NULL,
  `id_penjualan_bebas_detail` bigint(20) DEFAULT NULL,
  `id_komputer` int(11) NOT NULL,
  `tanggal_keluar` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stok_keluar` int(11) NOT NULL DEFAULT '0',
  `fixed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_barang_stok_masuk`
--

CREATE TABLE `ak_data_barang_stok_masuk` (
  `id_stok_barang_masuk` bigint(20) NOT NULL,
  `id_barang` bigint(20) NOT NULL,
  `id_pembelian` char(20) DEFAULT NULL,
  `id_komputer` tinyint(4) NOT NULL,
  `tanggal_masuk` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stok_masuk` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_barang_stok_masuk`
--

INSERT INTO `ak_data_barang_stok_masuk` (`id_stok_barang_masuk`, `id_barang`, `id_pembelian`, `id_komputer`, `tanggal_masuk`, `stok_masuk`, `deleted`) VALUES
(2, 6, NULL, 0, '2018-04-15 05:31:54', 100, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_instansi`
--

CREATE TABLE `ak_data_instansi` (
  `id_instansi` int(11) NOT NULL,
  `nm_instansi` char(45) NOT NULL,
  `alamat_instansi` text NOT NULL,
  `kontak_instansi` char(20) NOT NULL,
  `tuslah_racik` decimal(8,2) NOT NULL,
  `emblase_racik` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_instansi`
--

INSERT INTO `ak_data_instansi` (`id_instansi`, `nm_instansi`, `alamat_instansi`, `kontak_instansi`, `tuslah_racik`, `emblase_racik`) VALUES
(1, 'APOTEK CIREMAI FARMA', 'Jl. Raya Ciremai Mandirancan RT.027 RW.010, Desa Sampora Kec. Cilimus Kab. Kuningan', '-', '0.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_jenis_obat`
--

CREATE TABLE `ak_data_jenis_obat` (
  `id_jenis` char(20) NOT NULL COMMENT 'JEN00001',
  `nm_jenis` char(45) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_jenis_obat`
--

INSERT INTO `ak_data_jenis_obat` (`id_jenis`, `nm_jenis`, `deleted`) VALUES
('JEN00000', '-', 1),
('JEN00002', 'Alkes', 0),
('JEN00003', 'Narkotika', 0),
('JEN00004', 'Psikotropik', 0),
('JEN00005', 'Perkusor', 0),
('JEN00006', 'Bebas', 0),
('JEN00007', 'Bebas Terbatas', 0),
('JEN00008', 'Keras', 0),
('JEN00010', 'Tes', 1),
('JEN0009', 'Tes', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_kemasan`
--

CREATE TABLE `ak_data_kemasan` (
  `id_kemasan` char(20) NOT NULL COMMENT 'KEM00001',
  `nm_kemasan` char(45) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_kemasan`
--

INSERT INTO `ak_data_kemasan` (`id_kemasan`, `nm_kemasan`, `deleted`) VALUES
('JEN00011', 'Tes', 1),
('KEM00000', '-', 1),
('KEM00001', 'BOX', 0),
('KEM00002', 'PACK', 0),
('KEM00003', 'BLISTER', 0),
('KEM00004', 'STRIP', 0),
('KEM00005', 'BOTOL', 0),
('KEM00006', 'TUBE', 0),
('KEM00007', 'BUAH', 0),
('KEM00008', 'BOX', 0),
('KEM00009', 'BOTOL', 0),
('KEM00012', 'Tes', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_level`
--

CREATE TABLE `ak_data_level` (
  `id_level` int(11) NOT NULL,
  `nm_level` char(45) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_level`
--

INSERT INTO `ak_data_level` (`id_level`, `nm_level`, `deleted`) VALUES
(1, 'Master', 0),
(2, 'Owner', 0),
(3, 'Apoteker', 0),
(4, 'Gudang', 0),
(5, 'Kasir', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_log`
--

CREATE TABLE `ak_data_log` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_log`
--

INSERT INTO `ak_data_log` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('m3mrsn94tek7e5mvs9a9d0m8t4i3gbn5', '::1', 1520708502, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532303730373632323b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a31303a22495420537570706f7274223b7374617475737c733a353a22416b746966223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('t5ku51lh5mdqoreuule5vvbr9g7icd2t', '::1', 1521350562, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532313334333539363b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a31303a22495420537570706f7274223b7374617475737c733a353a22416b746966223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('v1d1clapft6o5h2t969nqignoi4c5ba3', '::1', 1521961829, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532313936313236393b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a31303a22495420537570706f7274223b7374617475737c733a353a22416b746966223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('huto5a64adjf8624n8daoc6sb3611qbc', '::1', 1522759903, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532323735393839343b),
('j50llvhekqc690av79gb3a3ibcp1pe68', '::1', 1523183326, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333138333231333b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c4e3b6c6576656c7c733a363a224d6173746572223b7374617475737c733a353a22416b746966223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('ultr4m1i1jgacg7l12hkfajadeqbrcpp', '::1', 1523217592, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333231373535383b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a363a224d6173746572223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('43l0b0htl4or1al1cas2maf9pvgap9b8', '::1', 1523643658, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333634333532393b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a363a224d6173746572223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('dmm5vhd615h30hckksne1abm6eh4hq3e', '::1', 1523695858, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333639353835373b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a363a224d6173746572223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('vp89598nq9h3ujslf7g1pdb053dgd1mt', '::1', 1523710168, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333731303036393b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a363a224d6173746572223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('teiperqcm6gsm42di0iof3pvs1rfrdeg', '::1', 1523745115, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333734343837303b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a363a224d6173746572223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b),
('41u4t454m8lmrla5qpkt9ppronpghlfi', '::1', 1523768435, 0x5f5f63695f6c6173745f726567656e65726174657c693a313532333736383431363b6b6f64657c733a383a225553523030303031223b6e616d617c733a363a224d6173746572223b757365727c733a363a226d6173746572223b6c6576656c7c733a363a224d6173746572223b637265617465647c733a31393a22323031382d30312d33302030303a34343a3430223b69734c6f67696e7c623a313b);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_margin`
--

CREATE TABLE `ak_data_margin` (
  `id_margin` char(20) NOT NULL COMMENT 'MAR00001',
  `nm_margin` char(45) NOT NULL,
  `persentase_margin` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_margin`
--

INSERT INTO `ak_data_margin` (`id_margin`, `nm_margin`, `persentase_margin`, `deleted`) VALUES
('MAR00000', '-', 0, 1),
('MAR00001', 'Resep', 10, 0),
('MAR00003', 'Narkotika', 0, 1),
('MAR00004', 'Bebas', 10, 0),
('MAR00005', 'Bebas Terbatas', 25, 0),
('MAR00006', 'Keras', 40, 0),
('MAR00007', 'Alkes', 0, 0),
('MAR00008', 'Paramedis', 15, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pabrik`
--

CREATE TABLE `ak_data_pabrik` (
  `id_pabrik` char(20) NOT NULL COMMENT 'PAB0001',
  `nm_pabrik` char(45) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_pabrik`
--

INSERT INTO `ak_data_pabrik` (`id_pabrik`, `nm_pabrik`, `deleted`) VALUES
('PAB00000', '-', 1),
('PAB00002', 'BAYER', 0),
('PAB00003', 'HEXFARM JAYA', 0),
('PAB00004', 'KIMIA FARMA', 0),
('PAB00005', 'GRAHA FARMA', 0),
('PAB00006', 'ERELA', 0),
('PAB00007', 'GLAKSO', 0),
('PAB00008', 'IFARS', 0),
('PAB00009', 'GALENIUM', 0),
('PAB00010', 'METISKA FARMA', 0),
('PAB00011', 'MOLEX AYUS', 0),
('PAB00012', 'DEXA MEDIKA', 0),
('PAB00013', 'INDO FARMA', 0),
('PAB00014', 'MOLEX AYUS', 0),
('PAB00015', 'NOVAPHARIN', 0),
('PAB00016', 'Sejahtera Lestari', 0),
('PAB00017', 'ERELA', 0),
('PAB00018', 'Marin Liza Farmasi', 0),
('PAB00019', 'HUFFA', 0),
('PAB00020', 'DARYA VARIA', 0),
('PAB00021', 'Pratama Nirmala', 0),
('PAB00022', 'NULABS', 0),
('PAB00023', 'MEF', 0),
('PAB00024', 'BERNOFARM', 0),
('PAB00025', 'BERLICO', 0),
('PAB00026', 'UNIVERSAL', 0),
('PAB00027', 'KALBE', 0),
('PAB00028', 'ZENITH', 0),
('PAB00029', 'Boehringer I', 0),
('PAB00030', 'ERRITA', 0),
('PAB00031', 'NOVEL', 0),
('PAB00032', 'NUFARINDO', 0),
('PAB00033', 'PROMED', 0),
('PAB00034', 'Clubros Farma', 0),
('PAB00035', 'CENDO', 0),
('PAB00036', 'Tunggal Idaman Abadi', 0),
('PAB00037', 'Lucas Djaja', 0),
('PAB00038', 'Novartis', 0),
('PAB00039', 'Safarindo Perdana', 0),
('PAB00040', 'A. Menarini', 0),
('PAB00041', 'HARSEN', 0),
('PAB00042', 'ERLIMPEX', 0),
('PAB00043', 'PHAPROS', 0),
('PAB00044', 'Afentis Farma', 0),
('PAB00045', 'Rechittbenchiser', 0),
('PAB00046', 'YARINDO', 0),
('PAB00047', 'SANOFI', 0),
('PAB00048', 'Ultra Santi', 0),
('PAB00049', 'MEIJI', 0),
('PAB00050', 'FAHRENHEIT', 0),
('PAB00051', 'First Medifarma', 0),
('PAB00052', 'MEDICOTRIMA', 0),
('PAB00053', 'MERCK', 0),
('PAB00054', 'ITRASAL', 0),
('PAB00055', 'ULTRA SAKTI', 0),
('PAB00056', 'Pharos', 0),
('PAB00057', 'JAYA MAS', 0),
('PAB00058', 'Sari Esensis Indah', 0),
('PAB00059', 'AFI Farma', 0),
('PAB00060', 'Medikon Prima', 0),
('PAB00061', 'LAPI', 0),
('PAB00062', 'Mega Surya Mas', 0),
('PAB00063', 'GLAXO', 0),
('PAB00064', 'John Francis Laboratories', 0),
('PAB00065', 'Danpac Farma', 0),
('PAB00066', 'Eagle Indo Pharma', 0),
('PAB00067', 'Eagle Indo Pharma', 0),
('PAB00068', 'Coronet Crown', 0),
('PAB00069', 'Chandra Nusantara', 0),
('PAB00070', 'IKAPHARMINDO', 0),
('PAB00071', 'Mahakam Beta Farma', 0),
('PAB00072', 'MEDIFARMA', 0),
('PAB00073', 'Tempo Scan', 0),
('PAB00074', 'HISAMITSU', 0),
('PAB00075', 'NELLCO', 0),
('PAB00076', 'ARTOS', 0),
('PAB00077', 'Jhonson & jhonson', 0),
('PAB00078', 'CIUBROS', 0),
('PAB00079', 'TAISHO', 0),
('PAB00080', 'SOHO', 0),
('PAB00081', 'Cotton Indo', 0),
('PAB00082', 'UNICHARM', 0),
('PAB00083', 'Reckit Benchiser', 0),
('PAB00084', 'Actavis', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pbf`
--

CREATE TABLE `ak_data_pbf` (
  `id_pbf` char(20) NOT NULL COMMENT 'PBF00001',
  `nm_pbf` char(45) NOT NULL,
  `alamat_pbf` text NOT NULL,
  `kota_pbf` char(45) NOT NULL,
  `kontak_kantor_pbf` char(20) DEFAULT NULL,
  `kontak_person_pbf` char(20) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_pbf`
--

INSERT INTO `ak_data_pbf` (`id_pbf`, `nm_pbf`, `alamat_pbf`, `kota_pbf`, `kontak_kantor_pbf`, `kontak_person_pbf`, `deleted`) VALUES
('PBF00000', '-', '-', '-', NULL, NULL, 1),
('PBF00001', 'PT. PBF 1', 'Jl. Banjaran', 'Cirebon', '023123123', '08771054562', 0),
('PBF00002', 'PT. PBF 2', 'Jl. Parujakan No. 13', 'Cirebon', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pembelian`
--

CREATE TABLE `ak_data_pembelian` (
  `id_pembelian` char(20) NOT NULL COMMENT 'FK2101180001',
  `id_pesanan` char(20) DEFAULT NULL,
  `id_pbf` char(20) NOT NULL,
  `id_user` char(20) NOT NULL,
  `tanggal_pembelian` date DEFAULT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `diskon` int(11) NOT NULL DEFAULT '0',
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `konsinyasi` tinyint(1) NOT NULL DEFAULT '0',
  `jenis` enum('Supplier','Gudang') NOT NULL DEFAULT 'Supplier',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pembelian_detail`
--

CREATE TABLE `ak_data_pembelian_detail` (
  `id_pembelian_detail` bigint(20) NOT NULL,
  `id_pembelian` char(20) DEFAULT NULL,
  `id_barang` bigint(20) NOT NULL,
  `id_komputer` int(11) NOT NULL DEFAULT '1',
  `qty` int(11) NOT NULL DEFAULT '0',
  `diskon` tinyint(100) DEFAULT '0',
  `subtotal_barang` decimal(10,2) NOT NULL DEFAULT '0.00',
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_pembelian_detail`
--

INSERT INTO `ak_data_pembelian_detail` (`id_pembelian_detail`, `id_pembelian`, `id_barang`, `id_komputer`, `qty`, `diskon`, `subtotal_barang`, `checked`, `deleted`) VALUES
(25, NULL, 6, 1, 100, NULL, '1000000.00', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_penjualan`
--

CREATE TABLE `ak_data_penjualan` (
  `id_penjualan` char(20) NOT NULL COMMENT 'TR12101180001',
  `id_user` char(20) NOT NULL,
  `tanggal_penjualan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pembulatan` decimal(8,2) NOT NULL DEFAULT '0.00',
  `bayar` decimal(8,2) NOT NULL DEFAULT '0.00',
  `kembali` decimal(8,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(8,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_penjualan`
--

INSERT INTO `ak_data_penjualan` (`id_penjualan`, `id_user`, `tanggal_penjualan`, `pembulatan`, `bayar`, `kembali`, `subtotal`, `status`, `deleted`) VALUES
('TR05031800015', 'USR00001', '2018-03-05 02:21:21', '0.00', '0.00', '0.00', '0.00', 1, 0),
('TR05031800016', 'USR00001', '2018-03-05 02:39:51', '80.00', '20000.00', '4500.00', '15500.00', 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_penjualan_bebas`
--

CREATE TABLE `ak_data_penjualan_bebas` (
  `id_penjualan_bebas` char(20) NOT NULL COMMENT 'TRB21011800001',
  `id_penjualan` char(20) NOT NULL,
  `nm_pasien` char(50) NOT NULL DEFAULT 'Tanpa Nama'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_penjualan_bebas`
--

INSERT INTO `ak_data_penjualan_bebas` (`id_penjualan_bebas`, `id_penjualan`, `nm_pasien`) VALUES
('B00018', 'TR05031800015', 'Tes'),
('B00019', 'TR05031800016', 'Tes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_penjualan_bebas_detail`
--

CREATE TABLE `ak_data_penjualan_bebas_detail` (
  `id_penjualan_bebas_detail` bigint(20) NOT NULL,
  `id_penjualan_bebas` char(20) DEFAULT NULL,
  `id_barang_detail` bigint(20) NOT NULL,
  `id_users` char(20) NOT NULL,
  `id_komputer` int(11) NOT NULL,
  `qty_penjualan_bebas` int(11) NOT NULL DEFAULT '0',
  `diskon_penjualan_bebas` int(11) NOT NULL DEFAULT '0',
  `pembulatan_penjualan_bebas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_penjualan_bebas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deleted_penjualan_bebas` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_penjualan_resep`
--

CREATE TABLE `ak_data_penjualan_resep` (
  `id_resep` char(20) NOT NULL COMMENT 'R00001',
  `id_penjualan` char(20) NOT NULL,
  `nomor_resep` char(20) DEFAULT NULL COMMENT 'R2101180001 / NR2101180001',
  `nm_pasien` char(45) NOT NULL,
  `nm_dokter` char(45) NOT NULL,
  `alamat` text NOT NULL,
  `kontak_pasien` char(20) DEFAULT NULL,
  `biaya_resep` decimal(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_penjualan_resep`
--

INSERT INTO `ak_data_penjualan_resep` (`id_resep`, `id_penjualan`, `nomor_resep`, `nm_pasien`, `nm_dokter`, `alamat`, `kontak_pasien`, `biaya_resep`) VALUES
('', '', NULL, '', '', '', NULL, '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_penjualan_resep_detail`
--

CREATE TABLE `ak_data_penjualan_resep_detail` (
  `id_penjualan_resep_detail` bigint(20) NOT NULL,
  `id_penjualan_resep` char(20) DEFAULT NULL,
  `id_barang_detail` bigint(20) NOT NULL,
  `id_users` char(20) NOT NULL,
  `etiket` text,
  `qty_penjualan_resep` int(11) NOT NULL,
  `diskon_penjualan_resep` int(11) NOT NULL DEFAULT '0',
  `pembulatan_penjualan_resep` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_penjualan_resep` decimal(8,2) NOT NULL DEFAULT '0.00',
  `deleted_penjualan_resep` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pesanan`
--

CREATE TABLE `ak_data_pesanan` (
  `id_pesanan` char(50) NOT NULL COMMENT 'SP21011800001',
  `id_pbf` char(20) NOT NULL,
  `id_user` char(20) NOT NULL,
  `tanggal_pembuatan` date NOT NULL,
  `jenis` enum('Supplier','Gudang') NOT NULL DEFAULT 'Supplier',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_pesanan`
--

INSERT INTO `ak_data_pesanan` (`id_pesanan`, `id_pbf`, `id_user`, `tanggal_pembuatan`, `jenis`, `deleted`) VALUES
('SP15041800001', 'PBF00001', 'USR00001', '2018-04-15', 'Supplier', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_pesanan_detail`
--

CREATE TABLE `ak_data_pesanan_detail` (
  `id_pesanan_detail` bigint(20) NOT NULL,
  `id_pesanan` char(20) DEFAULT NULL,
  `id_barang` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_pesanan_detail`
--

INSERT INTO `ak_data_pesanan_detail` (`id_pesanan_detail`, `id_pesanan`, `id_barang`, `qty`, `deleted`) VALUES
(30, 'SP15041800001', 6, 100, 0),
(31, 'SP15041800001', 5, 100, 0),
(32, 'SP15041800001', 57, 100, 0),
(33, 'SP15041800001', 1022, 100, 0),
(34, 'SP15041800001', 7, 100, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_racikan`
--

CREATE TABLE `ak_data_racikan` (
  `id_racikan` char(20) NOT NULL COMMENT 'RCK00001',
  `nm_racikan` char(50) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `harga_satuan` decimal(8,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_racikan_detail`
--

CREATE TABLE `ak_data_racikan_detail` (
  `id_racikan_detail` bigint(20) NOT NULL,
  `id_racikan` char(20) NOT NULL,
  `id_barang` bigint(20) NOT NULL,
  `qty_racikan_detail` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_racikan_detail_batch`
--

CREATE TABLE `ak_data_racikan_detail_batch` (
  `id_racikan_detail_batch` bigint(20) NOT NULL,
  `id_racikan_detail` bigint(20) NOT NULL,
  `id_barang_detail` bigint(20) NOT NULL,
  `qty_racikan_batch` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_satuan`
--

CREATE TABLE `ak_data_satuan` (
  `id_satuan` char(20) NOT NULL COMMENT 'SAT0001',
  `nm_satuan` char(45) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_satuan`
--

INSERT INTO `ak_data_satuan` (`id_satuan`, `nm_satuan`, `deleted`) VALUES
('SAT0000', '-', 1),
('SAT00001', 'TAB', 0),
('SAT00002', 'CAPSULE', 0),
('SAT00003', 'STRIP', 0),
('SAT00004', 'BLISTER', 0),
('SAT00005', 'BOTOL', 0),
('SAT00006', 'AMPULE', 0),
('SAT00007', 'VIAL', 0),
('SAT00008', 'TUBE', 0),
('SAT00009', 'PCS', 0),
('SAT00010', 'FLS', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_data_user`
--

CREATE TABLE `ak_data_user` (
  `id_user` char(20) NOT NULL COMMENT 'USR00001',
  `nm_user` char(45) NOT NULL,
  `login_user` char(45) NOT NULL,
  `pass_user` varchar(60) NOT NULL,
  `level_user` int(11) NOT NULL,
  `login_terakhir` char(20) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_data_user`
--

INSERT INTO `ak_data_user` (`id_user`, `nm_user`, `login_user`, `pass_user`, `level_user`, `login_terakhir`, `created_date`, `deleted`) VALUES
('USR00001', 'Master', 'master', '$2y$10$pecyvbJsq/UFRqr7giyiOOG1YuIy5qMztsZWCLwHhKXkKV8IQvVUe', 1, '2018-04-15', '2018-01-30 00:44:40', 0);

--
-- Trigger `ak_data_user`
--
DELIMITER $$
CREATE TRIGGER `ak_trigger_data_users` BEFORE INSERT ON `ak_data_user` FOR EACH ROW BEGIN
	INSERT INTO ak_sequence_data_user VALUES (NULL);
	SET NEW.id_user = CONCAT('USR', LPAD(LAST_INSERT_ID(), 5, '0'));
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_sequence_data_pembelian`
--

CREATE TABLE `ak_sequence_data_pembelian` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_sequence_data_pembelian`
--

INSERT INTO `ak_sequence_data_pembelian` (`id`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_sequence_data_penjualan`
--

CREATE TABLE `ak_sequence_data_penjualan` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_sequence_data_penjualan`
--

INSERT INTO `ak_sequence_data_penjualan` (`id`) VALUES
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_sequence_data_penjualan_bebas`
--

CREATE TABLE `ak_sequence_data_penjualan_bebas` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_sequence_data_penjualan_bebas`
--

INSERT INTO `ak_sequence_data_penjualan_bebas` (`id`) VALUES
(3),
(4),
(5),
(7),
(8),
(9),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_sequence_data_user`
--

CREATE TABLE `ak_sequence_data_user` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ak_sequence_data_user`
--

INSERT INTO `ak_sequence_data_user` (`id`) VALUES
(1),
(2),
(3),
(5),
(9),
(10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ak_view_report_penjualan_bebas`
--

CREATE TABLE `ak_view_report_penjualan_bebas` (
  `Tanggal` datetime DEFAULT NULL,
  `Nama Pasien` char(50) DEFAULT NULL,
  `Nama Barang` char(45) DEFAULT NULL,
  `Jumlah` int(11) DEFAULT NULL,
  `Harga Jual` decimal(8,2) DEFAULT NULL,
  `Total Harga` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ak_data_barang`
--
ALTER TABLE `ak_data_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `ak_data_barang_detail`
--
ALTER TABLE `ak_data_barang_detail`
  ADD PRIMARY KEY (`id_detail_barang`);

--
-- Indexes for table `ak_data_barang_stok_masuk`
--
ALTER TABLE `ak_data_barang_stok_masuk`
  ADD PRIMARY KEY (`id_stok_barang_masuk`);

--
-- Indexes for table `ak_data_jenis_obat`
--
ALTER TABLE `ak_data_jenis_obat`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `ak_data_kemasan`
--
ALTER TABLE `ak_data_kemasan`
  ADD PRIMARY KEY (`id_kemasan`);

--
-- Indexes for table `ak_data_level`
--
ALTER TABLE `ak_data_level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `ak_data_margin`
--
ALTER TABLE `ak_data_margin`
  ADD PRIMARY KEY (`id_margin`);

--
-- Indexes for table `ak_data_pabrik`
--
ALTER TABLE `ak_data_pabrik`
  ADD PRIMARY KEY (`id_pabrik`);

--
-- Indexes for table `ak_data_pbf`
--
ALTER TABLE `ak_data_pbf`
  ADD PRIMARY KEY (`id_pbf`);

--
-- Indexes for table `ak_data_pembelian`
--
ALTER TABLE `ak_data_pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indexes for table `ak_data_pembelian_detail`
--
ALTER TABLE `ak_data_pembelian_detail`
  ADD PRIMARY KEY (`id_pembelian_detail`);

--
-- Indexes for table `ak_data_penjualan`
--
ALTER TABLE `ak_data_penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `ak_data_penjualan_bebas`
--
ALTER TABLE `ak_data_penjualan_bebas`
  ADD PRIMARY KEY (`id_penjualan_bebas`);

--
-- Indexes for table `ak_data_pesanan`
--
ALTER TABLE `ak_data_pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indexes for table `ak_data_pesanan_detail`
--
ALTER TABLE `ak_data_pesanan_detail`
  ADD PRIMARY KEY (`id_pesanan_detail`);

--
-- Indexes for table `ak_data_satuan`
--
ALTER TABLE `ak_data_satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `ak_data_user`
--
ALTER TABLE `ak_data_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ak_data_barang_detail`
--
ALTER TABLE `ak_data_barang_detail`
  MODIFY `id_detail_barang` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ak_data_barang_stok_masuk`
--
ALTER TABLE `ak_data_barang_stok_masuk`
  MODIFY `id_stok_barang_masuk` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ak_data_pembelian_detail`
--
ALTER TABLE `ak_data_pembelian_detail`
  MODIFY `id_pembelian_detail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `ak_data_pesanan_detail`
--
ALTER TABLE `ak_data_pesanan_detail`
  MODIFY `id_pesanan_detail` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
