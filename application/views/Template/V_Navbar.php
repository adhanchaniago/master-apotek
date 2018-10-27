<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar navbar-static-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand"><b>SISTEM</b> RETAIL</a>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
				<i class="fa fa-bars"></i>
			</button>
		</div>
		<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="<?php if($Nav=='Dashboard') : echo 'active'; endif; ?>"><a href="<?= base_url('dashboard') ?>"><i class="fa fa-home"></i> Dashboard <span class="sr-only">(current)</span></a></li>
				<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
					<li class="dropdown <?php if($Nav=='Master Data') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-cube"></i>
							Master Data 
							<span class="fa fa-caret-down"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('master/jenis') ?>">Jenis Obat</a></li>
							<li><a href="<?= base_url('master/kemasan') ?>">Kemasan</a></li>
							<li><a href="<?= base_url('master/satuan') ?>">Satuan</a></li>
							<li><a href="<?= base_url('master/pabrik') ?>">Pabrik</a></li>
							<li><a href="<?= base_url('master/supplier') ?>">PBF</a></li>
							<li><a href="<?= base_url('master/barang') ?>">Barang</a></li>
							<li><a href="<?= base_url('master/dokter') ?>">Dokter</a></li>
							<li><a href="<?= base_url('master/karyawan') ?>">Karyawan</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Transaksi') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Transaksi <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('transaksi/pemesanan') ?>">Pemesanan</a></li>
							<li><a href="<?= base_url('transaksi/pembelian') ?>">Pembelian</a></li>
							<li><a href="<?= base_url('transaksi/penjualan/bebas') ?>">Penjualan Bebas</a></li>
							<li><a href="<?= base_url('transaksi/penjualan/resep') ?>">Penjualan Resep</a></li>
							<li><a href="<?= base_url('transaksi/retur/penjualan/bebas') ?>">Retur Penjualan Bebas</a></li>
							<li><a href="<?= base_url('transaksi/retur/penjualan/resep') ?>">Retur Penjualan Resep</a></li>
							<li><a href="<?= base_url('transaksi/retur/pembelian') ?>">Retur Pembelian</a></li>
							<li><a href="<?= base_url('transaksi/penyesuaian') ?>">Penyesuaian</a></li>
							<li><a href="<?= base_url('transaksi/pelunasan/hutang') ?>">Pelunasan Hutang</a></li>
							<li><a href="<?= base_url('transaksi/pelunasan/piutang/bebas') ?>">Pelunasan Bon Obat Bebas</a></li>
							<li><a href="<?= base_url('transaksi/pelunasan/piutang/resep') ?>">Pelunasan Bon Resep</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Laporan') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i> Laporan <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('laporan/pemesanan') ?>">Laporan Pemesanan</a></li>
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Pembelian <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/pembelian') ?>">Semua Pembelian</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/pembelian-barang-pabrik') ?>">Barang dan Pabrik</a></li>
								</ul>
							</li>
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Penjualan <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/pj-bebas') ?>">Penjualan Bebas</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/pj-resep') ?>">Penjualan Resep</a></li>
								</ul>
							</li>
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Retur <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/retur-penjualan') ?>">Retur Penjualan</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/retur-pembelian') ?>">Retur Pembelian</a></li>
								</ul>
							</li>
							<li><a href="<?= base_url('laporan/stok-inhand') ?>">Laporan Kartu Stok</a></li>
							<li><a href="<?= base_url('laporan/stok-opname') ?>">Laporan Penyesuaian</a></li>
							<li><a href="<?= base_url('laporan/piutang') ?>">Laporan Piutang</a></li>
							<li><a href="<?= base_url('laporan/hutang') ?>">Laporan Hutang</a></li>
							<li><a href="<?= base_url('laporan/cashflow') ?>">Laporan Cashflow</a></li>
							<li><a href="<?= base_url('laporan/laba-rugi') ?>">Laporan Laba Rugi</a></li>
						</ul>
					</li>
					<li class="<?php if($Nav=='Setup') : echo 'active'; endif; ?>"><a href="<?= base_url('setup') ?>"><i class="fa fa-gears"></i> Konfigurasi <span class="sr-only">(current)</span></a></li>
				<?php elseif($Level=="Kasir") : ?>
					<li class="dropdown <?php if($Nav=='Master Data') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-cube"></i>
							Master Data 
							<span class="fa fa-caret-down"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('master/karyawan') ?>">Karyawan</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Transaksi') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Transaksi <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('transaksi/pj-bebas') ?>">Penjualan Bebas</a></li>
							<!-- <li><a href="<?= base_url('transaksi/pj-resep') ?>">Penjualan Resep</a></li> -->
							<li><a href="<?= base_url('transaksi/retur-obat') ?>">Retur</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Laporan') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i> Laporan <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('laporan/piutang') ?>">Laporan Piutang</a></li>
						</ul>
					</li>
				<?php elseif($Level=="Gudang") : ?>
					<li class="dropdown <?php if($Nav=='Master Data') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-cube"></i>
							Master Data 
							<span class="fa fa-caret-down"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('master/jenis-barang') ?>">Jenis Barang</a></li>
							<li><a href="<?= base_url('master/kemasan') ?>">Kemasan</a></li>
							<li><a href="<?= base_url('master/satuan') ?>">Satuan</a></li>
							<li><a href="<?= base_url('master/pabrik') ?>">Pabrik</a></li>
							<li><a href="<?= base_url('master/pbf') ?>">Supplier</a></li>
							<!-- <li><a href="<?= base_url('master/margin') ?>">Index Margin</a></li> -->
							<li><a href="<?= base_url('master/barang') ?>">Barang</a></li>
							<li><a href="<?= base_url('master/karyawan') ?>">Karyawan</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Transaksi') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Transaksi <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('transaksi/pemesanan') ?>">Pemesanan</a></li>
							<li><a href="<?= base_url('transaksi/pembelian') ?>">Pembelian</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Laporan') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i> Laporan <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Pembelian <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/pembelian-barang-pabrik') ?>">Barang dan Pabrik</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/pembelian-surat-pesanan') ?>">Surat Pesanan</a></li>
								</ul>
							</li>
							<li><a href="<?= base_url('laporan/penerimaan-barang') ?>">Laporan Penerimaan Barang</a></li>
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Stok <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/stok-inhand') ?>">Inhand</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/stok-opname') ?>">Opname</a></li>
								</ul>
							</li>
							<li><a href="<?= base_url('laporan/piutang') ?>">Laporan Piutang</a></li>
							<li><a href="<?= base_url('laporan/hutang') ?>">Laporan Hutang</a></li>
						</ul>
					</li>
				<?php elseif($Level=="Apoteker") : ?>
					<li class="dropdown <?php if($Nav=='Master Data') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-cube"></i>
							Master Data 
							<span class="fa fa-caret-down"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('master/jenis-obat') ?>">Jenis Obat</a></li>
							<li><a href="<?= base_url('master/kemasan') ?>">Kemasan</a></li>
							<li><a href="<?= base_url('master/satuan') ?>">Satuan</a></li>
							<li><a href="<?= base_url('master/pabrik') ?>">Pabrik</a></li>
							<li><a href="<?= base_url('master/pbf') ?>">PBF</a></li>
							<li><a href="<?= base_url('master/barang') ?>">Barang</a></li>
							<li><a href="<?= base_url('master/karyawan') ?>">Karyawan</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Transaksi') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Transaksi <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?= base_url('transaksi/pemesanan') ?>">Pemesanan</a></li>
							<li><a href="<?= base_url('transaksi/pembelian') ?>">Pembelian</a></li>
							<li><a href="<?= base_url('transaksi/pj-bebas') ?>">Penjualan Bebas</a></li>
							<li><a href="<?= base_url('transaksi/pj-resep') ?>">Penjualan Resep</a></li>
							<li><a href="<?= base_url('transaksi/retur-obat') ?>">Retur</a></li>
						</ul>
					</li>
					<li class="dropdown <?php if($Nav=='Laporan') : echo 'active'; endif; ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i> Laporan <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Pembelian <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/pembelian-barang-pabrik') ?>">Barang dan Pabrik</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/pembelian-surat-pesanan') ?>">Surat Pesanan</a></li>
								</ul>
							</li>
							<li><a href="<?= base_url('laporan/penerimaan-barang') ?>">Laporan Penerimaan Barang</a></li>
							<li class="dropdown-submenu">
								<a class="test" tabindex="-1" href="#">Laporan Stok <span class="fa fa-caret-right"></span></a>
								<ul class="dropdown-menu">
									<li><a tabindex="-1" href="<?= base_url('laporan/stok-inhand') ?>">Inhand</a></li>
									<li><a tabindex="-1" href="<?= base_url('laporan/stok-opname') ?>">Opname</a></li>
								</ul>
							</li>
							<li><a href="<?= base_url('laporan/piutang') ?>">Laporan Piutang</a></li>
							<li><a href="<?= base_url('laporan/hutang') ?>">Laporan Hutang</a></li>
						</ul>
					</li>
				<?php endif ?>
			</ul>
		</div>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="<?= base_url('assets/AdminLTE/img/avatar5.png') ?>" class="user-image" alt="User Image">
						<span class="hidden-xs"><?= $Nama ?></span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<img src="<?= base_url('assets/AdminLTE/img/avatar5.png') ?>" class="img-circle" alt="User Image">
							<p>
								<?= $Nama ?> - <?= $Level; ?>
								<small>Aktifitas Sejak <?= $this->session->userdata('created') ?></small>
							</p>
						</li>
						<li class="user-footer">
							<div class="pull-left">
								<a href="#" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="<?= base_url('dashboard/logout') ?>" class="btn btn-default btn-flat">Keluar</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>