<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Apoteker") : ?>
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
						<i class="fa fa-bars"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Kode</th>
								<th>Nama Pabrik</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<span class="pull-left">
							<i class="fa fa-pencil"></i>
							<h3 class="box-title">
								Form <?= $Title ?>
							</h3>
						</span>
						<span class="pull-right">
							<a onclick="reload()" class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i></a>
							<a href="<?= base_url() ?>" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
						</span>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="id_pabrik">Kode Pabrik</label>
									<?= form_input('id_pabrik',null,array(
																			'id' => 'id_pabrik',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			//'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="nm_pabrik">Nama Pabrik</label>
									<?= form_input('nm_pabrik',null,array(
																			'id' => 'nm_pabrik',
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
					"url": "<?= base_url('pabrik/list_all_data') ?>",
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
				var id_pabrik = $("#id_pabrik").val();
				var nm_pabrik = $("#nm_pabrik").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('pabrik/tambah_data/') ?>",
						dataType: 'json',
						data: {
								id_pabrik : id_pabrik,
								nm_pabrik : nm_pabrik
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_pabrik").attr("disabled","true");
	        				$("#register")[0].reset();
	        				$("#nm_pabrik").focus();
	        				$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
		});
		$(document).on('click','#getdata',function() {
	  		var id_pabrik = $(this).attr("data");
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pabrik/get_data/') ?>",
				dataType: 'json',
				data: {
						id_pabrik : id_pabrik
					},
				success: function(data) {
					$("#id_pabrik").val(data.id_pabrik);
					$("#nm_pabrik").val(data.nm_pabrik);
					$("#Hapus").removeAttr("disabled");
					//$("#id_pabrik").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
	  		var id_pabrik = $("#id_pabrik").val();
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pabrik/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_pabrik : id_pabrik
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_pabrik").attr("disabled","true");
    				$('#register')[0].reset();
    				$('#nm_pabrik').focus();
    				$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		function reload() {
			$("#Hapus").attr("disabled","true");
			$("#id_pabrik").attr("disabled","true");
			$("#register")[0].reset();
			$("#nm_pabrik").focus();
		}
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    //$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>