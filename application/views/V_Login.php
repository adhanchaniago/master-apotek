<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>SISTEM RETAIL - <?= $Title ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
		<link rel="stylesheet" href="<?= base_url('assets/AdminLTE/css/AdminLTE.css') ?>">
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<span style="font-size:0.8em;"><?= $Instansi->nm_instansi ?></span>
				<span style="font-size:0.4em;display:block">
					<?= $Instansi->alamat_instansi ?> | 
					<?= $Instansi->kontak_instansi ?>
				</span>
			</div>
			<?php 
				$str = $this->session->flashdata('item');
				$x = explode("-",$str) 
			?>
			<div class="login-box-body">
				<?php if ($str==NULL) : ?>
					<p class="login-box-msg">Silahkan masukkan username dan password untuk menggunakan aplikasi</p>
				<?php else : ?>
				<div class="alert alert-<?= $x[0] ?>">
					<h4><i class="icon fa fa-ban"></i> Error</h4>
						<?= $x[1] ?>
				</div>
				<?php endif; ?>
				<?= form_open('portal/proses-login') ?>
					<div class="form-group has-feedback">
						<?php
							$data = array(
								'name' => 'usr',
								'class' => 'form-control',
								'placeholder' => 'Masukkan username di sini',
								'autocomplete' => 'off',
								'required' => 'true'
							);
							echo form_input($data);
						?>
						<span class="fa fa-user form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<?php
							$data = array(
								'name' => 'pwd',
								'class' => 'form-control',
								'placeholder' => 'Masukkan password di sini',
								'autocomplete' => 'off',
								'required' => 'true'
							);
							echo form_password($data);
						?>
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
						</div>
					</div>
				<?= form_close() ?>
				<br />
				<p class="login-box-msg">
					&copy; 2017 - <?= date('Y') ?><br />
					Developed with <i style="color:red" class="fa fa-heart"></i> by <a href="https://akasakapratama.web.id/">Gilang Pratama</a>
				</p>
			</div>
		</div>
		<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
		<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
		<script src="<?= base_url('assets/jquery/jquery.slimscroll.min.js') ?>"></script>
		<script src="<?= base_url('assets/dist/js/fastclick.js') ?>"></script>
		<script src="<?= base_url('assets/adminLTE/js/adminlte.min.js') ?>"></script>
		<script>
			$(document).ready(function () {
				$('.sidebar-menu').tree()
			})
		</script>
	</body>
</html>