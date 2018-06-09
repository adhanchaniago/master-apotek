<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>SISTEM RETAIL - <?= $Title ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/daterangepicker.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap-datepicker.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/select2/select2.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/AdminLTE/css/AdminLTE.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/AdminLTE/css/skins/skin-purple.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/sweet-alert/sweetalert.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/datatables/datatables.min.css'); ?>">
		<link rel="stylesheet" href="<?= base_url('assets/pace/themes/purple/pace-theme-center-atom.css'); ?>">
		<style>
			.ui-autocomplete {
				max-height: 100px;
				overflow-y: auto;
				/* prevent horizontal scrollbar */
				overflow-x: hidden;
			}
			* html .ui-autocomplete {
				height: 100px;
			}
			.cover{
				position: fixed;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				z-index: 1999;
				background: #34495e;
			}
			/* .dataTables_wrapper { min-height: 300px; } */
			.scroll {
				position:fixed;
				right:20px;
				bottom:20px;
				background:#b2b2b2;
				background:rgba(178,178,178,0.7);
				padding:15px;
				border-radius: 5px;
				text-align: center;
				margin: 0 0 0 0;
				cursor:pointer;
				transition: 0.5s;
				-moz-transition: 0.5s;
				-webkit-transition: 0.5s;
				-o-transition: 0.5s; 		
			}
			.scroll .fa {
				font-size:30px;
				margin-top:-5px;
				margin-left:1px;
				transition: 0.5s;
				-moz-transition: 0.5s;
				-webkit-transition: 0.5s;
				-o-transition: 0.5s; 	
			}
			.dropdown-submenu {
				position: relative;
			}

			.dropdown-submenu .dropdown-menu {
				top: 0;
				left: 100%;
				margin-top: -1px;
			}
		</style>
		<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
		<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
		<script src="<?= base_url('assets/jquery/jquery.slimscroll.min.js') ?>"></script>
		<script src="<?= base_url('assets/dist/js/fastclick.js') ?>"></script>
		<script src="<?= base_url('assets/AdminLTE/js/adminlte.min.js') ?>"></script>
		<script src="<?= base_url('assets/select2/select2.full.min.js') ?>"></script>
		<script src="<?= base_url('assets/datatables/datatables.min.js') ?>"></script>
		<script src="<?= base_url('assets/sweet-alert/sweetalert.min.js') ?>"></script>
		<script src="<?= base_url('assets/pace/pace.min.js') ?>"></script>
		<script>Pace.on("done", function(){ $(".cover").fadeOut(1000); });</script>
	</head>
	<body class="hold-transition skin-purple layout-top-nav">
		<a id="up" href="#down"></a>
		<div class="up"></div>
		<div class="wrapper">
			<div class="cover"></div>
			<header class="main-header">
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
											<li><a href="<?= base_url('master/jenis-obat') ?>">Jenis Obat</a></li>
											<li><a href="<?= base_url('master/kemasan') ?>">Kemasan</a></li>
											<li><a href="<?= base_url('master/satuan') ?>">Satuan</a></li>
											<li><a href="<?= base_url('master/pabrik') ?>">Pabrik</a></li>
											<li><a href="<?= base_url('master/pbf') ?>">PBF</a></li>
											<li><a href="<?= base_url('master/margin') ?>">Index Margin</a></li>
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
											<li><a href="<?= base_url('transaksi/pj-bebas') ?>">Penjualan Bebas</a></li>
											<li><a href="<?= base_url('transaksi/pj-resep') ?>">Penjualan Resep</a></li>
											<li><a href="<?= base_url('transaksi/retur-penjualan-bebas') ?>">Retur Penjualan Bebas</a></li>
											<li><a href="<?= base_url('transaksi/retur-penjualan-resep') ?>">Retur Penjualan Resep</a></li>
											<li><a href="<?= base_url('transaksi/retur-pembelian') ?>">Retur Pembelian</a></li>
											<li><a href="<?= base_url('transaksi/penyesuaian') ?>">Penyesuaian</a></li>
											<li><a href="<?= base_url('transaksi/pelunasan-hutang') ?>">Pelunasan Hutang</a></li>
											<li><a href="<?= base_url('transaksi/pelunasan-piutang-bebas') ?>">Pelunasan Bon Obat Bebas</a></li>
											<li><a href="<?= base_url('transaksi/pelunasan-piutang-resep') ?>">Pelunasan Bon Resep</a></li>
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
			</header>
			<div class="content-wrapper">
				<?php $this->load->view($Konten) ?>
			</div>
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					Page rendered in <strong>{elapsed_time}</strong> seconds.
				</div>
				<strong>Copyright &copy; <?php if(date('Y')!="2017") : echo "2017-".date('Y'); endif ?></strong> Coded by <a href="https://akasakapratama.web.id/">Akasaka Pratama</a>. All rights reserved.
			</footer>
		</div>
		<script>
			$(document).ready(function () {
				$('.sidebar-menu').tree();
				$('#up, #down').on('click', function(e){
		    		e.preventDefault();
		    		var target= $(this).get(0).id == 'up' ? $('#down') : $('#up');
		    		$('html, body').stop().animate({
		       			scrollTop: target.offset().top
		    		}, 1000);
				});
				$('.dropdown-submenu a.test').on("click", function(e){
					$(this).next('ul').toggle();
					e.stopPropagation();
					e.preventDefault();
				});
			})
		</script>
	</body>
</html>