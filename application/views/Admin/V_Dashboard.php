<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="IT Support") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> Dashboard Admin</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-cube"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Data Barang</span>
						<span class="info-box-number"><a href="<?= base_url('barang') ?>" class="btn btn-xs btn-danger">Klik di sini</a></span>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-gears"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Index Margin</span>
						<span class="info-box-number"><a href="<?= base_url('margin') ?>" class="btn btn-xs btn-danger">Klik di sini</a></span>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Akses Pengguna</span>
						<span class="info-box-number"><a href="<?= base_url('user') ?>" class="btn btn-xs btn-danger">Klik di sini</a></span>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-handshake-o"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Piutang</span>
						<span class="info-box-number"><a href="<?= base_url('report/piutang') ?>" class="btn btn-xs btn-danger">Klik di sini</a></span>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-widget widget-user-2">
					<div class="widget-user-header bg-red">
						<div class="widget-user-image">
							<img class="img-circle" src="<?= base_url('assets/images/icon.png') ?>" alt="User Avatar">
						</div>
						<h3 class="widget-user-username"><?= $Instansi->nm_instansi ?></h3>
						<h5 class="widget-user-desc"><?= $Instansi->alamat_instansi ?> | <?= $Instansi->kontak_instansi ?></h5>
					</div>
					<div class="box-footer no-padding">
						<ul class="nav nav-stacked">
							<li><a>Tuslah Racik <span class="pull-right"><?= "Rp. ".number_format($Instansi->tuslah_racik) ?></span></a></li>
							<li><a>Emblase Racik <span class="pull-right"><?= "Rp. ".number_format($Instansi->emblase_racik) ?></span></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-6"></div>
			<div class="col-lg-6">
				<div class="box box-danger">
					<div class="box-header"><h4 class="text-center">Data User</h4></div>
					<div class="box-body">
						<table class="table table-responsive table-bordered table-striped">
							<thead>
								<th>Kode</th>
								<th>Nama</th>
								<th>Username</th>
								<th>Level</th>
								<th>Status</th>
							</thead>
							<tbody>
								<?php foreach($Users as $u) : ?>
									<tr>
										<td><?= $u->id_user ?></td>
										<td><?= $u->nm_user ?></td>
										<td><?= $u->username ?></td>
										<td><?= $u->nm_level ?></td>
										<td><?= $u->status_user ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif ?>