<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
		<section class="content-header">
			<h1><?= $Title ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li> <?= $Nav ?></li>
				<li class="active"> <?= $Title ?></li>
			</ol>
		</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-cog"></i>
						<h3 class="box-title"><?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtInstansi" class="table table-striped table-bordered">
							<thead>
								<th>Nama Instansi</th>
								<th>Alamat Instansi</th>
								<th>Kontak Instansi</th>
								<th>Tuslah Racik</th>
								<th>Emblase Racik</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-pencil"></i>
						<h3 class="box-title">Form <?= $Title ?></h3>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							
							<div class="col-lg-6">
								<div class="form-group">
									<?= form_input('id_instansi',null,array(
																			'id' => 'id_instansi',
																			'class' => 'hidden',
																			//'placeholder' => 'Username',
																			// 'required' => 'true',
																			// 'disabled' => 'true'
																		));
									?>
									<label for="nm_instansi">Nama Instansi</label>
									<?= form_input('nm_instansi',null,array(
																			'id' => 'nm_instansi',
																			'class' => 'form-control',
																			//'placeholder' => 'Username',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="kontak_instansi">Kontak Intansi</label>
									<?= form_input('kontak_instansi',null,array(
																			'id' => 'kontak_instansi',
																			'class' => 'form-control',
																			//'placeholder' => 'Username',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="tuslah_racik">Tuslah Racik</label>
									<?= form_input('tuslah_racik',null,array(
																			'id' => 'tuslah_racik',
																			'class' => 'form-control',
																			//'placeholder' => 'Password',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="emblase_racik">Emblase Racik</label>
									<?= form_input('emblase_racik',null,array(
																			'id' => 'emblase_racik',
																			'class' => 'form-control',
																			//'placeholder' => 'Password',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>

							<div class="col-lg-12">
								<div class="form-group">
									<label for="alamat_instansi">Alamat Instansi</label>
									<?= form_input('alamat_instansi',null,array(
																			'id' => 'alamat_instansi',
																			'class' => 'form-control',
																			//'placeholder' => 'Password',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="submit" id="Simpan" class="btn btn-block btn-primary" disabled="true">Simpan</button>
							</span>							
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.extensions.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var dtUser = $('#dtInstansi').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('setup/list_data_instansi/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": true,
				"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_instansi = $("#id_instansi").val();
				var nm_instansi = $("#nm_instansi").val();
				var alamat_instansi = $("#alamat_instansi").val();
				var kontak_instansi = $("#kontak_instansi").val();
				var tuslah_racik = $("#tuslah_racik").val();
				var emblase_racik = $("#emblase_racik").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('setup/update_data/') ?>",
						dataType: 'json',
						data: {
								id_instansi : id_instansi,
								nm_instansi : nm_instansi,
								alamat_instansi : alamat_instansi,
								kontak_instansi : kontak_instansi,
								tuslah_racik : tuslah_racik,
								emblase_racik : emblase_racik
							},
						success: function(data) {
							$("#dtInstansi").DataTable().ajax.reload();
							$("#Simpan").attr("disabled","true");
	        				$("#register")[0].reset();
	        				$("#nm_kemasan").focus();
	        				$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
		});
		$(document).on('click','#getdata',function() {
	  		var id_instansi = $(this).attr("data");
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('setup/get_data/') ?>",
				dataType: 'json',
				data: {
						id_instansi : id_instansi
					},
				success: function(data) {
					$("#id_instansi").val(data.id_instansi);
					$("#nm_instansi").val(data.nm_instansi);
					$("#alamat_instansi").val(data.alamat_instansi);
					$("#kontak_instansi").val(data.kontak_instansi);
					$("#tuslah_racik").val(data.tuslah_racik);
					$("#emblase_racik").val(data.emblase_racik);
					$("#Simpan").removeAttr("disabled");
				}
			});
		});
	</script>
<?php endif ?>