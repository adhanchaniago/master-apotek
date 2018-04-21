<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-wheelchair"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Kode</th>
								<th>Nama </th>
								<th>Alamat </th>
								<th>Kota </th>
								<th>Kontak Kantor</th>
								<th>Kontak Person</th>
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
									<label for="id_pbf">Kode</label>
									<?= form_input('id_pbf',null,array(
																			'id' => 'id_pbf',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			//'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="nm_pbf">Nama</label>
									<?= form_input('nm_pbf',null,array(
																			'id' => 'nm_pbf',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="alamat_pbf">Alamat</label>
									<?= form_input('alamat_pbf',null,array(
																			'id' => 'alamat_pbf',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="kota_pbf">Kota</label>
									<?= form_input('kota_pbf',null,array(
																			'id' => 'kota_pbf',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="kontak_kantor_pbf">Kontak Kantor</label>
									<?= form_input('kontak_kantor_pbf',null,array(
																			'id' => 'kontak_kantor_pbf',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="kontak_person_pbf">Kontak Person</label>
									<?= form_input('kontak_person_pbf',null,array(
																			'id' => 'kontak_person_pbf',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="submit" class="btn btn-block btn-primary">Simpan</button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" id="Hapus" class="btn btn-block btn-danger" disabled="true">Hapus</button>
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
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('pbf/list_all_data') ?>",
					"type": "POST"
				},
				"autoWidth": false,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_pbf = $("#id_pbf").val();
				var nm_pbf = $("#nm_pbf").val();
				var alamat_pbf = $("#alamat_pbf").val();
				var kota_pbf = $("#kota_pbf").val();
				var kontak_person_pbf = $("#kontak_person_pbf").val();
				var kontak_kantor_pbf = $("#kontak_kantor_pbf").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('pbf/tambah_data/') ?>",
						dataType: 'json',
						data: {
								id_pbf : id_pbf,
								nm_pbf : nm_pbf,
								alamat_pbf : alamat_pbf,
								kota_pbf : kota_pbf,
								kontak_kantor_pbf : kontak_kantor_pbf,
								kontak_person_pbf : kontak_person_pbf
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_pbf").attr("disabled","true");
	        				$("#register")[0].reset();
	        				$("#nm_pbf").focus();
	        				$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
		});
		$(document).on('click','#getdata',function() {
	  		var id_pbf = $(this).attr("data");
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pbf/get_data/') ?>",
				dataType: 'json',
				data: {
						id_pbf : id_pbf
					},
				success: function(data) {
					$("#id_pbf").val(data.id_pbf);
					$("#nm_pbf").val(data.nm_pbf);
					$("#alamat_pbf").val(data.alamat_pbf);
					$("#kota_pbf").val(data.kota_pbf);
					$("#kontak_person_pbf").val(data.kontak_person_pbf);
					$("#kontak_kantor_pbf").val(data.kontak_kantor_pbf);
					$("#Hapus").removeAttr("disabled");
					$("#id_pbf").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
	  		var id_pbf = $("#id_pbf").val();
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pbf/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_pbf : id_pbf
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_pbf").attr("disabled","true");
    				$('#register')[0].reset();
    				$('#nm_pbf').focus();
    				$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    //$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>