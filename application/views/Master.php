<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>SISTEM APOTEK - <?= $Title ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/select2/dist/css/select2.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/adminLTE/css/AdminLTE.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/adminLTE/css/skins/skin-red.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/sweet-alert/sweetalert.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/DataTables/datatables.min.css'); ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-datepicker.min.css'); ?>">
		<link rel="stylesheet" href="<?= base_url('assets/css/pace-theme-center-atom.css'); ?>">
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
		</style>
		<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
		<script src="<?= base_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
		<script src="<?= base_url('assets/jquery/jquery.slimscroll.js') ?>"></script>
		<script src="<?= base_url('assets/fastclick/lib/fastclick.js') ?>"></script>
		<script src="<?= base_url('assets/adminLTE/js/adminlte.min.js') ?>"></script>
		<script src="<?= base_url('assets/input-mask/jquery.inputmask.js') ?>"></script>
		<script src="<?= base_url('assets/input-mask/jquery.inputmask.date.extensions.js') ?>"></script>
		<script src="<?= base_url('assets/select2/dist/js/select2.full.min.js') ?>"></script>
		<script src="<?= base_url('assets/DataTables/datatables.min.js') ?>"></script>
		<script src="<?= base_url('assets/sweet-alert/sweetalert.min.js') ?>"></script>
		<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
		<script src="<?= base_url('assets/js/pace.js') ?>"></script>
		<script>
			Pace.on("done", function(){
				$(".cover").fadeOut(1000);
			});
		</script>
	</head>
	<body class="hold-transition skin-red layout-top-nav">
		<div class="wrapper">
			<div class="cover"></div>
			<header class="main-header">
				<nav class="navbar navbar-static-top">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand"><b>SISTEM</b> APOTEK</a>
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
								<i class="fa fa-bars"></i>
							</button>
						</div>
						<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
							<?php if($Level=="IT Support") : ?>
								<ul class="nav navbar-nav">
									<li class="<?php if($Nav=='Dashboard') : echo 'active'; endif; ?>"><a href="<?= base_url('dashboard') ?>"><i class="fa fa-home"></i> Dashboard <span class="sr-only">(current)</span></a></li>
									<li class="dropdown <?php if($Nav=='Master Data') : echo 'active'; endif; ?>">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cube"></i> Master Data <span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
											<li><a href="<?= base_url('jenis-obat') ?>">Jenis Obat</a></li>
											<li><a href="<?= base_url('kemasan') ?>">Kemasan</a></li>
											<li><a href="<?= base_url('satuan') ?>">Satuan</a></li>
											<li><a href="<?= base_url('pabrik') ?>">Pabrik</a></li>
											<li><a href="<?= base_url('pbf') ?>">PBF</a></li>
											<li><a href="<?= base_url('barang') ?>">Barang</a></li>
											<li><a href="<?= base_url('margin') ?>">Index Margin</a></li>
											<li><a href="<?= base_url('user') ?>">Pengguna</a></li>
										</ul>
									</li>
									<li class="dropdown <?php if($Nav=='Pembelian') : echo 'active'; endif; ?>">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Transaksi <span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
											<li><a href="<?= base_url('penjualan/pj-resep') ?>">Penjualan Resep</a></li>
											<li><a href="<?= base_url('penjualan/pj-non-resep') ?>">Penjualan Non Resep</a></li>
											<li><a href="<?= base_url('pesanan') ?>">Pemesanan</a></li>
											<li><a href="<?= base_url('pembelian') ?>">Pembelian</a></li>
											<li><a href="<?= base_url('penyesuaian') ?>">Penyesuaian Stok</a></li>
											<li><a href="<?= base_url('retur-obat') ?>">Retur Obat</a></li>
										</ul>
									</li>
									<li class="dropdown <?php if($Nav=='Laporan') : echo 'active'; endif; ?>">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-archive"></i> Laporan <span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
											<li><a href="<?= base_url('report/pendapatan') ?>">Pendapatan</a></li>
											<li><a href="<?= base_url('report/persediaan') ?>">Persediaan</a></li>
											<li><a href="<?= base_url('report/pembelian') ?>">Pembelian</a></li>
											<li><a href="<?= base_url('report/kartu-stok') ?>">Kartu Stok</a></li>
											<li><a href="<?= base_url('report/hutang') ?>">Hutang</a></li>
											<li><a href="<?= base_url('report/piutang') ?>">Piutang</a></li>
											<li><a href="<?= base_url('report/penyesuaian') ?>">Penyesuaian</a></li>
											<li><a href="<?= base_url('report/shu') ?>">SHU</a></li>
										</ul>
									</li>
								</ul>
							<?php endif ?>
						</div>
						<div class="navbar-custom-menu">
							<ul class="nav navbar-nav">
								<li class="dropdown user user-menu">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<img src="<?= base_url('assets/adminLTE/img/avatar5.png') ?>" class="user-image" alt="User Image">
										<span class="hidden-xs"><?= $Nama ?></span>
									</a>
									<ul class="dropdown-menu">
										<li class="user-header">
											<img src="<?= base_url('assets/adminLTE/img/avatar5.png') ?>" class="img-circle" alt="User Image">
											<p>
												<?= $Nama ?> - <?= $Level; ?>
												<small>Member since <?= $this->session->userdata('created') ?></small>
											</p>
										</li>
										<li class="user-footer">
											<div class="pull-left">
												<a href="#" class="btn btn-default btn-flat">Profile</a>
											</div>
											<div class="pull-right">
												<a href="<?= base_url('dashboard/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
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
				<strong>Copyright &copy; <?php if(date('Y')!="2017") : echo "2017-".date('Y'); endif ?></strong> Coded by <a href="https://akasakapratama.web.id/">Gilang Pratama</a>. All rights reserved.
			</footer>
		</div>
		<script>
		$(document).ready(function () {
			$('.sidebar-menu').tree()
		})
		</script>
	</body>
</html>